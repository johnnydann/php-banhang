<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    private $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * Lấy tất cả sự kiện
     */
    public function getAllEvents(): JsonResponse
    {
        try {
            $events = $this->eventRepository->getAll();
            return response()->json($events);
        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy danh sách sự kiện: ' . $e->getMessage());
            return response()->json(['error' => 'Lỗi máy chủ nội bộ'], 500);
        }
    }

    /**
     * Lấy sự kiện theo ID
     */
    public function getEventById(int $id): JsonResponse
    {
        try {
            $event = $this->eventRepository->getById($id);
            
            if (!$event) {
                return response()->json(['error' => 'Không tìm thấy sự kiện'], 404);
            }
            
            return response()->json($event);
        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy sự kiện: ' . $e->getMessage());
            return response()->json(['error' => 'Lỗi máy chủ nội bộ'], 500);
        }
    }

    /**
     * Thêm sự kiện mới
     */
    public function addEvent(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'image' => 'nullable|image|max:2048'
            ]);

            $event = new Event();
            $event->title = $request->title;
            $event->description = $request->description;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            
            // Xử lý upload hình ảnh
            if ($request->hasFile('image')) {
                $event->image = $this->saveImage($request->file('image'));
            }

            $this->eventRepository->add($event);

            return response()->json($event, 201);
        } catch (\Exception $e) {
            Log::error('Lỗi khi thêm sự kiện: ' . $e->getMessage());
            return response()->json(['error' => 'Lỗi máy chủ nội bộ: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Cập nhật sự kiện
     */
    public function updateEvent(Request $request, int $id): JsonResponse
    {
        try {
            $event = $this->eventRepository->getById($id);
            
            if (!$event) {
                return response()->json(['error' => 'Không tìm thấy sự kiện'], 404);
            }

            if ($request->isJson()) {
            // Validation cho JSON request
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'image' => 'nullable|string'
            ]);
            } else {
                // Validation cho Form request
                $request->validate([
                    'title' => 'required|string|max:255',
                    'description' => 'required|string',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
                    'image' => 'nullable|image|max:2048'
                ]);
            }

            // Cập nhật các thuộc tính
            $event->title = $request->title;
            $event->description = $request->description;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;

            if ($request->has('image') && $request->image !== $event->image) {
                // Nếu là file từ form-data
                if ($request->hasFile('image')) {
                    // Xóa hình ảnh cũ nếu có
                    if ($event->image && file_exists(public_path($event->image))) {
                        unlink(public_path($event->image));
                    }
                    $event->image = $this->saveImage($request->file('image'));
                } 
                // Nếu là đường dẫn từ JSON
                else {
                    $event->image = $request->image;
                }
            }

            $this->eventRepository->update($event);

            return response()->json($event);
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật sự kiện: ' . $e->getMessage());
            return response()->json(['error' => 'Lỗi máy chủ nội bộ: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Xóa sự kiện
     */
    public function deleteEvent(int $id): JsonResponse
    {
        try {
            $event = $this->eventRepository->getById($id);
            
            if (!$event) {
                return response()->json(['error' => 'Không tìm thấy sự kiện'], 404);
            }

            // Xóa hình ảnh liên quan nếu có
            if ($event->image && file_exists(public_path($event->image))) {
                unlink(public_path($event->image));
            }

            $this->eventRepository->delete($id);

            return response()->json(['message' => 'Xóa sự kiện thành công']);
        } catch (\Exception $e) {
            Log::error('Lỗi khi xóa sự kiện: ' . $e->getMessage());
            return response()->json(['error' => 'Lỗi máy chủ nội bộ: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Lưu hình ảnh vào thư mục public/eventImages
     */
    private function saveImage($imageFile): string
    {
        try {
            // Tạo thư mục nếu chưa tồn tại
            $directory = public_path('eventImages');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Tạo tên file ngẫu nhiên để tránh trùng lặp
            $fileName = uniqid() . '_' . $imageFile->getClientOriginalName();
            
            // Di chuyển file vào thư mục đích
            $imageFile->move($directory, $fileName);
            
            // Trả về đường dẫn tương đối
            return '/eventImages/' . $fileName;
        } catch (\Exception $e) {
            Log::error('Lỗi lưu ảnh: ' . $e->getMessage());
            throw new \Exception('Lỗi lưu ảnh: ' . $e->getMessage());
        }
    }
}