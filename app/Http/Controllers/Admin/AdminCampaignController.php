<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminCampaignController extends Controller
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

    public function index()
    {
        $campaigns = Campaign::with(['creator', 'donations'])
            ->withCount('donations')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $users = User::where('is_admin', false)->get();
        return view('admin.campaigns.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:1',
            'end_date' => 'nullable|date|after:today',
            'is_active' => 'boolean',
            'is_priority' => 'boolean',
            'creator_id' => 'required|exists:users,id',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'عنوان الحملة مطلوب',
            'description.required' => 'وصف الحملة مطلوب',
            'target_amount.required' => 'المبلغ المستهدف مطلوب',
            'target_amount.min' => 'المبلغ المستهدف يجب أن يكون أكبر من صفر',
            'end_date.after' => 'تاريخ الانتهاء يجب أن يكون في المستقبل',
            'creator_id.required' => 'منشئ الحملة مطلوب',
            'creator_id.exists' => 'منشئ الحملة غير موجود',
            'images.max' => 'يمكن رفع 5 صور كحد أقصى',
            'images.*.image' => 'يجب أن تكون الملفات صور',
            'images.*.max' => 'حجم الصورة يجب أن يكون أقل من 2 ميجابايت',
        ]);

        // Handle image uploads
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('campaign_images', 'public');
                $images[] = $path;
            }
        }

        Campaign::create([
            'title' => $request->title,
            'description' => $request->description,
            'target_amount' => $request->target_amount,
            'current_amount' => 0,
            'end_date' => $request->end_date,
            'is_active' => $request->boolean('is_active', true),
            'is_priority' => $request->boolean('is_priority', false),
            'creator_id' => $request->creator_id,
            'images' => $images,
        ]);

        return redirect()->route('admin.campaigns.index')->with('success', 'تم إنشاء الحملة بنجاح');
    }

    public function show(Campaign $campaign)
    {
        $campaign->load(['creator', 'donations.user']);
        return view('admin.campaigns.show', compact('campaign'));
    }

    public function edit(Campaign $campaign)
    {
        $users = User::where('is_admin', false)->get();
        return view('admin.campaigns.edit', compact('campaign', 'users'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:1',
            'end_date' => 'nullable|date',
            'is_active' => 'boolean',
            'is_priority' => 'boolean',
            'creator_id' => 'required|exists:users,id',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_images' => 'nullable|array',
        ]);

        // Handle image removal
        $currentImages = $campaign->images ?? [];
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
                    $path = $image->store('campaign_images', 'public');
                    $currentImages[] = $path;
                }
            }
        }

        $campaign->update([
            'title' => $request->title,
            'description' => $request->description,
            'target_amount' => $request->target_amount,
            'end_date' => $request->end_date,
            'is_active' => $request->boolean('is_active'),
            'is_priority' => $request->boolean('is_priority'),
            'creator_id' => $request->creator_id,
            'images' => array_values($currentImages),
        ]);

        return redirect()->route('admin.campaigns.index')->with('success', 'تم تحديث الحملة بنجاح');
    }

    public function destroy(Campaign $campaign)
    {
        // Delete campaign images
        if ($campaign->images) {
            foreach ($campaign->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $campaign->delete();

        return redirect()->route('admin.campaigns.index')->with('success', 'تم حذف الحملة بنجاح');
    }
}
