<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CampaignResource;
use App\Http\Resources\CampaignUpdateResource;
use App\Models\Campaign;
use App\Models\CampaignUpdate;
use App\Models\UserFavoriteCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CampaignController extends Controller
{
    /**
     * @OA\Get(
     *     path="/campaigns",
     *     summary="الحصول على قائمة الحملات",
     *     tags={"Campaigns"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="رقم الصفحة",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="عدد العناصر في الصفحة",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="البحث في العنوان والوصف",
     *         required=false,
     *         @OA\Schema(type="string", example="مساعدة")
     *     ),
     *     @OA\Parameter(
     *         name="priority",
     *         in="query",
     *         description="الحملات ذات الأولوية فقط",
     *         required=false,
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="قائمة الحملات",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CampaignResource")),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=5),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=50)
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        $priority = $request->get('priority');

        $query = Campaign::active()
            ->with(['creator', 'donations', 'latestUpdate.creator'])
            ->withCount(['donations', 'updates']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($priority) {
            $query->priority();
        }

        $campaigns = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => CampaignResource::collection($campaigns->items()),
            'meta' => [
                'current_page' => $campaigns->currentPage(),
                'last_page' => $campaigns->lastPage(),
                'per_page' => $campaigns->perPage(),
                'total' => $campaigns->total(),
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/campaigns",
     *     summary="إنشاء حملة جديدة",
     *     tags={"Campaigns"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title","description","target_amount"},
     *                 @OA\Property(property="title", type="string", example="مساعدة الأسر المحتاجة"),
     *                 @OA\Property(property="description", type="string", example="حملة لمساعدة الأسر المحتاجة في رمضان"),
     *                 @OA\Property(property="target_amount", type="number", format="float", example=10000.00),
     *                 @OA\Property(property="images[]", type="array", @OA\Items(type="string", format="binary"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="تم إنشاء الحملة بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم إنشاء الحملة بنجاح"),
     *             @OA\Property(property="data", ref="#/components/schemas/CampaignResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="خطأ في البيانات",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="خطأ في البيانات"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:1',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'عنوان الحملة مطلوب',
            'description.required' => 'وصف الحملة مطلوب',
            'target_amount.required' => 'المبلغ المستهدف مطلوب',
            'target_amount.numeric' => 'المبلغ المستهدف يجب أن يكون رقم',
            'target_amount.min' => 'المبلغ المستهدف يجب أن يكون أكبر من صفر',
            'images.max' => 'يمكن رفع 5 صور كحد أقصى',
            'images.*.image' => 'يجب أن تكون الملفات صور',
            'images.*.max' => 'حجم الصورة يجب أن يكون أقل من 2 ميجابايت',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات',
                'errors' => $validator->errors()
            ], 422);
        }

        $campaign = Campaign::create([
            'title' => $request->title,
            'description' => $request->description,
            'target_amount' => $request->target_amount,
            'current_amount' => 0,
            'user_id' => auth()->id(),
            'is_active' => true,
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('campaigns', 'public');
                $imagePaths[] = $path;
            }
            $campaign->update(['images' => json_encode($imagePaths)]);
        }

        $campaign->load(['creator', 'donations']);

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الحملة بنجاح',
            'data' => new CampaignResource($campaign)
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/campaigns/{id}",
     *     summary="الحصول على تفاصيل حملة",
     *     tags={"Campaigns"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="معرف الحملة",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تفاصيل الحملة",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/CampaignResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="الحملة غير موجودة",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="الحملة غير موجودة")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $campaign = Campaign::with(['creator', 'donations.user', 'latestUpdate.creator'])
            ->withCount(['donations', 'updates'])
            ->find($id);

        if (!$campaign) {
            return response()->json([
                'success' => false,
                'message' => 'الحملة غير موجودة'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new CampaignResource($campaign)
        ]);
    }

    /**
     * @OA\Post(
     *     path="/campaigns/{id}/favorite",
     *     summary="إضافة/إزالة حملة من المفضلة",
     *     tags={"Campaigns"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="معرف الحملة",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم تحديث المفضلة بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم إضافة الحملة للمفضلة"),
     *             @OA\Property(property="is_favorited", type="boolean", example=true)
     *         )
     *     )
     * )
     */
    public function toggleFavorite($id)
    {
        $campaign = Campaign::find($id);
        if (!$campaign) {
            return response()->json([
                'success' => false,
                'message' => 'الحملة غير موجودة'
            ], 404);
        }

        $user = auth()->user();
        $favorite = $user->favorites()->where('campaign_id', $id)->first();

        if ($favorite) {
            $favorite->delete();
            $message = 'تم إزالة الحملة من المفضلة';
            $isFavorited = false;
        } else {
            $user->favorites()->create(['campaign_id' => $id]);
            $message = 'تم إضافة الحملة للمفضلة';
            $isFavorited = true;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_favorited' => $isFavorited
        ]);
    }

    /**
     * @OA\Get(
     *     path="/my-campaigns",
     *     summary="الحصول على حملات المستخدم",
     *     tags={"Campaigns"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="حملات المستخدم",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CampaignResource"))
     *         )
     *     )
     * )
     */
    public function myCampaigns()
    {
        $campaigns = auth()->user()->campaigns()
            ->with(['creator', 'donations'])
            ->withCount('donations')
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => CampaignResource::collection($campaigns->items()),
            'meta' => [
                'current_page' => $campaigns->currentPage(),
                'last_page' => $campaigns->lastPage(),
                'per_page' => $campaigns->perPage(),
                'total' => $campaigns->total(),
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/favorites",
     *     summary="الحصول على الحملات المفضلة",
     *     tags={"Campaigns"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="الحملات المفضلة",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CampaignResource"))
     *         )
     *     )
     * )
     */
    public function favorites()
    {
        $campaigns = auth()->user()->favoriteCampaigns()
            ->with(['creator', 'donations'])
            ->withCount('donations')
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => CampaignResource::collection($campaigns->items()),
            'meta' => [
                'current_page' => $campaigns->currentPage(),
                'last_page' => $campaigns->lastPage(),
                'per_page' => $campaigns->perPage(),
                'total' => $campaigns->total(),
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/campaigns/{id}/updates",
     *     summary="الحصول على تحديثات الحملة",
     *     tags={"Campaigns"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         @OA\Schema(type="integer", default=10, maximum=50)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تحديثات الحملة",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CampaignUpdateResource")),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=5),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=50)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="الحملة غير موجودة",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="الحملة غير موجودة")
     *         )
     *     )
     * )
     */
    public function getUpdates($id, Request $request)
    {
        $campaign = Campaign::active()->find($id);
        if (!$campaign) {
            return response()->json([
                'success' => false,
                'message' => 'الحملة غير موجودة'
            ], 404);
        }

        $perPage = min($request->get('per_page', 10), 50);

        $updates = $campaign->updates()
            ->with(['creator'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => CampaignUpdateResource::collection($updates->items()),
            'meta' => [
                'current_page' => $updates->currentPage(),
                'last_page' => $updates->lastPage(),
                'per_page' => $updates->perPage(),
                'total' => $updates->total(),
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/campaigns/{id}/updates/{updateId}",
     *     summary="الحصول على تحديث محدد للحملة",
     *     tags={"Campaigns"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="updateId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تفاصيل التحديث",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/CampaignUpdateResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="الحملة أو التحديث غير موجود",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="التحديث غير موجود")
     *         )
     *     )
     * )
     */
    public function getUpdate($id, $updateId)
    {
        $campaign = Campaign::active()->find($id);
        if (!$campaign) {
            return response()->json([
                'success' => false,
                'message' => 'الحملة غير موجودة'
            ], 404);
        }

        $update = $campaign->updates()->with(['creator'])->find($updateId);
        if (!$update) {
            return response()->json([
                'success' => false,
                'message' => 'التحديث غير موجود'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new CampaignUpdateResource($update)
        ]);
    }
}
