<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminCampaignUpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->is_admin) {
                abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Campaign $campaign)
    {
        $updates = $campaign->updates()
            ->with(['creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.campaign-updates.index', compact('campaign', 'updates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Campaign $campaign)
    {
        return view('admin.campaign-updates.create', compact('campaign'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Campaign $campaign)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:general,medical,financial,progress,urgent',
            'is_important' => 'boolean',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'عنوان التحديث مطلوب',
            'content.required' => 'محتوى التحديث مطلوب',
            'type.required' => 'نوع التحديث مطلوب',
            'type.in' => 'نوع التحديث غير صحيح',
            'images.max' => 'لا يمكن رفع أكثر من 5 صور',
            'images.*.image' => 'يجب أن تكون الملفات صور',
            'images.*.mimes' => 'صيغة الصورة يجب أن تكون: jpeg, png, jpg, gif',
            'images.*.max' => 'حجم الصورة يجب أن يكون أقل من 2 ميجابايت',
        ]);

        // Handle image uploads
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('campaign_update_images', 'public');
                $images[] = $path;
            }
        }

        CampaignUpdate::create([
            'campaign_id' => $campaign->id,
            'title' => $request->title,
            'content' => $request->get("content"),
            'type' => $request->type,
            'is_important' => $request->boolean('is_important', false),
            'images' => $images,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.campaign-updates.index', $campaign)
            ->with('success', 'تم إضافة التحديث بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign, CampaignUpdate $update)
    {
        // Ensure the update belongs to the campaign
        if ($update->campaign_id !== $campaign->id) {
            abort(404);
        }

        $update->load(['creator']);
        return view('admin.campaign-updates.show', compact('campaign', 'update'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campaign $campaign, CampaignUpdate $update)
    {
        // Ensure the update belongs to the campaign
        if ($update->campaign_id !== $campaign->id) {
            abort(404);
        }

        return view('admin.campaign-updates.edit', compact('campaign', 'update'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campaign $campaign, CampaignUpdate $update)
    {
        // Ensure the update belongs to the campaign
        if ($update->campaign_id !== $campaign->id) {
            abort(404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:general,medical,financial,progress,urgent',
            'is_important' => 'boolean',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_images' => 'nullable|array',
        ], [
            'title.required' => 'عنوان التحديث مطلوب',
            'content.required' => 'محتوى التحديث مطلوب',
            'type.required' => 'نوع التحديث مطلوب',
            'type.in' => 'نوع التحديث غير صحيح',
            'images.max' => 'لا يمكن رفع أكثر من 5 صور',
            'images.*.image' => 'يجب أن تكون الملفات صور',
            'images.*.mimes' => 'صيغة الصورة يجب أن تكون: jpeg, png, jpg, gif',
            'images.*.max' => 'حجم الصورة يجب أن يكون أقل من 2 ميجابايت',
        ]);

        // Handle image removal
        $currentImages = $update->images ?? [];
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imageToRemove) {
                if (in_array($imageToRemove, $currentImages)) {
                    Storage::disk('public')->delete($imageToRemove);
                    $currentImages = array_filter($currentImages, function ($img) use ($imageToRemove) {
                        return $img !== $imageToRemove;
                    });
                }
            }
        }

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if (count($currentImages) < 5) {
                    $path = $image->store('campaign_update_images', 'public');
                    $currentImages[] = $path;
                }
            }
        }

        $update->update([
            'title' => $request->title,
            'content' => $request->get("content"),
            'type' => $request->type,
            'is_important' => $request->boolean('is_important'),
            'images' => array_values($currentImages),
        ]);

        return redirect()->route('admin.campaign-updates.index', $campaign)
            ->with('success', 'تم تحديث التحديث بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign, CampaignUpdate $update)
    {
        // Ensure the update belongs to the campaign
        if ($update->campaign_id !== $campaign->id) {
            abort(404);
        }

        // Delete update images
        if ($update->images) {
            foreach ($update->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $update->delete();

        return redirect()->route('admin.campaign-updates.index', $campaign)
            ->with('success', 'تم حذف التحديث بنجاح');
    }
}
