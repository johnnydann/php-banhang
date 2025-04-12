@extends('admin.dashboard')
@section('page_title', 'Quản lý sự kiện')

@section('events')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <div>
            <h2 class="text-2xl font-bold text-brand-600 dark:text-brand-400">🎉 Danh sách sự kiện</h2>
            <p class="text-gray-500 text-sm mt-1">Quản lý các sự kiện trong hệ thống</p>
        </div>
        <div class="flex items-center space-x-4">
            <!-- Thanh tìm kiếm -->
            <div class="relative">
                <input id="searchEvent" type="text" placeholder="🔍 Tìm sự kiện" 
                    class="w-64 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-500" 
                    autocomplete="off">
                <!-- Phần hiển thị gợi ý -->
                <div id="suggestion-container" class="mt-2 flex flex-wrap gap-2 hidden"></div>
            </div>
            
            <!-- Nút Thêm Sự Kiện -->
            <button id="openAddEventForm"
                class="bg-gradient-to-r from-green-500 to-green-600 text-black px-5 py-2 rounded-full shadow hover:from-green-600 hover:to-green-700 transition duration-200">
                + Thêm sự kiện
            </button>
        </div>
    </div>

    <!-- Form thêm sự kiện -->
    <div id="addEventForm" class="hidden bg-orange-50 dark:bg-gray-900 p-6 rounded-lg mb-6 border border-dashed border-brand-300" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input id="eventTitle" type="text" placeholder="🎯 Tên sự kiện"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-500" />
            <textarea id="eventDescription" placeholder="🖊 Mô tả... "
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-500"></textarea>
            <input id="eventStartDate" type="datetime-local" 
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-500" />
            <input id="eventEndDate" type="datetime-local" 
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-500" />
            
            <div>
                <label for="eventImage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">🖼️ Hình ảnh sự kiện</label>
                <div class="flex items-center gap-4">
                    <input id="eventImage" type="file" accept="image/*" class="hidden" />
                    <button type="button" onclick="document.getElementById('eventImage').click()"
                        class="bg-gray-100 px-3 py-1 rounded border text-sm hover:bg-gray-200">
                        📁 Chọn ảnh
                    </button>
                    <img id="eventImagePreview" src="" alt="Preview ảnh"
                        class="w-20 h-20 object-contain border rounded shadow hidden" />
                </div>
            </div>
        </div>
        <div class="text-right mt-4">
            <button id="closeAddEventForm"
                class="bg-brand-500 text-red px-5 py-2 rounded-lg hover:bg-brand-600 transition shadow"
                style="color: red;">
                ✖ Đóng
            </button>
            <button id="submitAddEvent"
                class="bg-brand-500 text-black px-5 py-2 rounded-lg hover:bg-brand-600 transition shadow">
                ✅ Thêm sự kiện
            </button>
        </div>
    </div>
    
    <!-- Modal Cập nhật sự kiện -->
    <div id="updateEventModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[100] hidden" onclick="hideUpdateEventModal()">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl max-w-sm text-left animate-fadeIn" onclick="event.stopPropagation()">
            <h3 class="text-2xl font-bold text-blue-600 mb-2">📝 Cập nhật sự kiện</h3>
            <input id="updateEventTitle" type="text" placeholder="Tên sự kiện" class="w-full mb-3 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
            <textarea id="updateEventDescription" placeholder="Mô tả..." class="w-full mb-3 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500"></textarea>
            <input id="updateEventStartDate" type="datetime-local" class="w-full mb-3 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
            <input id="updateEventEndDate" type="datetime-local" class="w-full mb-3 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
            
            <div>
                <label for="updateEventImage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">🖼️ Hình ảnh sự kiện</label>
                <div class="flex items-center gap-4">
                    <input id="updateEventImage" type="file" accept="image/*" class="hidden" />
                    <button type="button" onclick="document.getElementById('updateEventImage').click()" class="bg-gray-100 px-3 py-1 rounded border text-sm hover:bg-gray-200">
                        📁 Chọn ảnh
                    </button>
                    <img id="updateEventImagePreview" src="" alt="Preview ảnh" class="w-20 h-20 object-contain border rounded shadow hidden" />
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button onclick="hideUpdateEventModal()" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 text-black">✖ Hủy</button>
                <button onclick="submitUpdateEvent()" class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white">✅ Lưu</button>
            </div>
        </div>
    </div>


    <!-- Success Modal -->
    <div id="successEventModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[100] hidden">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl max-w-sm text-center animate-fadeIn">
            <h3 class="text-2xl font-bold text-green-600 mb-2">🎉 Thêm thành công!</h3>
            <p class="text-gray-600 dark:text-gray-300 text-sm">Sự kiện mới đã được thêm vào hệ thống.</p>
            <button onclick="hideSuccessEventModal()"
                class="mt-4 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition">
                ✖ Đóng
            </button>
        </div>
    </div>

    <!-- Danh sách sự kiện -->
    <div id="event-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- JS render here -->
    </div>

    <!-- Modal Xem chi tiết sự kiện -->
    <div id="viewEventModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[100] hidden"
        onclick="hideViewEventModal()">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl max-w-lg w-full text-left animate-fadeIn"
            onclick="event.stopPropagation()">
            <h3 class="text-2xl font-bold text-blue-600 mb-2">👁️ Chi tiết sự kiện</h3>
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-1"><strong>Tên sự kiện:</strong> <span id="viewEventTitle"></span></p>
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-1"><strong>Mô tả:</strong> <span id="viewEventDescription"></span></p>
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-1"><strong>Thời gian bắt đầu:</strong> <span id="viewEventStartDate"></span></p>
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-1"><strong>Thời gian kết thúc:</strong> <span id="viewEventEndDate"></span></p>
            <div class="text-center mt-4">
                <img id="viewEventImage" src="" alt="Ảnh sự kiện"
                    class="max-w-full max-h-60 object-contain border rounded shadow">
            </div>
            <div class="text-right mt-4">
                <button onclick="hideViewEventModal()"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    ✖ Đóng
                </button>
            </div>
        </div>
    </div>
    
    <!-- Modal xác nhận xóa sự kiện -->
    <div id="deleteEventModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[100] hidden"
        onclick="hideDeleteEventModal()">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl max-w-sm text-center animate-fadeIn"
            onclick="event.stopPropagation()">
            <h3 class="text-xl font-bold text-red-600 mb-2">⚠️ Xóa sự kiện</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Bạn có chắc chắn muốn xóa sự kiện này không?</p>
            <div class="flex justify-center gap-4">
                <button onclick="hideDeleteEventModal()"
                    class="bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded">
                    ✖ Hủy
                </button>
                <button onclick="confirmDeleteEvent()"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                    🗑️ Xóa
                </button>
            </div>
        </div>
    </div>

    <div id="pagination" class="flex justify-center gap-2 mt-6">
        <!-- Phân trang sẽ được render tại đây -->
    </div>
</div>

<script>
    let currentPage = 1;
    const pageSize = 3; // 6 sự kiện mỗi trang

    // Load các sự kiện
    document.addEventListener('DOMContentLoaded', function() {
        fetchEvents();
    });

    // Mở form thêm sự kiện
    document.getElementById('openAddEventForm').onclick = () => {
        document.getElementById('addEventForm').classList.toggle('hidden');
    };

    // Đóng form thêm sự kiện
    document.getElementById('closeAddEventForm').onclick = () => {
        document.getElementById('addEventForm').classList.add('hidden');
    };

    // Thêm sự kiện
    document.getElementById('submitAddEvent').onclick = async () => {
        const title = document.getElementById('eventTitle').value;
        const description = document.getElementById('eventDescription').value;
        const startDate = document.getElementById('eventStartDate').value;
        const endDate = document.getElementById('eventEndDate').value;
        const imageFile = document.getElementById('eventImage').files[0];

        if (!title || !description || !startDate || !endDate) {
            alert('Vui lòng điền đầy đủ thông tin!');
            return;
        }

        const formData = new FormData();
        formData.append('title', title);
        formData.append('description', description);
        formData.append('start_date', startDate);
        formData.append('end_date', endDate);
        if (imageFile) formData.append('image', imageFile);

        const response = await fetch('/api/events/add', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
            },
            body: formData
        });

        const data = await response.json();

        if (response.ok) {
            fetchEvents();  // Fetch the updated list of events
            document.getElementById('addEventForm').classList.add('hidden');  // Hide the form
            showSuccessEventModal(); // Show success modal
            resetEventForm();  // Reset the form fields after adding the event
        } else {
            alert('Lỗi khi thêm sự kiện');
        }
    };

    // Function to reset the event form
    function resetEventForm() {
        // Clear all fields
        document.getElementById('eventTitle').value = '';
        document.getElementById('eventDescription').value = '';
        document.getElementById('eventStartDate').value = '';
        document.getElementById('eventEndDate').value = '';
        document.getElementById('eventImage').value = '';  // Clear the file input

        // Hide the image preview
        document.getElementById('eventImagePreview').classList.add('hidden');
    }

    // Show success modal
    function showSuccessEventModal() {
        document.getElementById('successEventModal').classList.remove('hidden');
    }

    // Hide success modal
    function hideSuccessEventModal() {
        document.getElementById('successEventModal').classList.add('hidden');
    }

    // Event listener to handle image preview
    document.getElementById('eventImage').addEventListener('change', function() {
        const file = this.files[0];
        const preview = document.getElementById('eventImagePreview');

        if (file) {
            // Create a URL for the file
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');  // Show the preview image
        } else {
            preview.classList.add('hidden');  // Hide the preview if no file is selected
        }
    });


    // Lấy danh sách sự kiện
    function fetchEvents() {
        fetch(`/api/events/getall?pageNumber=${currentPage}`)
        .then(res => res.json())
        .then(data => {
            if (Array.isArray(data) && data.length > 0) {
                // Render events
                const list = document.getElementById('event-list');
                list.innerHTML = data.map(event => ` 
                    <div class="p-4 border rounded shadow bg-white dark:bg-gray-700">
                        <h3 class="font-bold text-brand-600 dark:text-brand-400">${event.title}</h3>
                        <p class="text-sm text-gray-500">Thời gian: ${new Date(event.start_date).toLocaleString()}</p>
                        <p class="text-sm text-gray-500">Thời gian kết thúc: ${new Date(event.end_date).toLocaleString()}</p>
                        <div class="flex justify-center">
                            <img src="${event.image ? event.image : '/default.jpg'}"
                                alt="${event.title}" class="max-w-sm max-h-48 object-contain rounded mb-3">
                        </div>
                        <div class="mt-2 flex gap-2">
                            <button onclick="viewEvent(${event.id})" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm transition">
                                👁 Xem
                            </button>
                            <button onclick="deleteEvent(${event.id})" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm transition">
                                🗑 Xóa
                            </button>
                            <button onclick="openUpdateEventModal(${event.id})" class="px-3 py-1 bg-yellow-400 text-black rounded hover:bg-yellow-500 text-sm transition">
                                ✏️ Sửa
                            </button>
                        </div>
                    </div>
                `).join('');
                renderPagination(data.total_pages);
            } else {
                console.log("No events found or incorrect response format");
            }
        })
        .catch(error => console.error("Error fetching events:", error));
    }

    // Phân trang
    function renderPagination(totalPages) {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('button');
            pageButton.innerText = i;
            pageButton.className = `px-3 py-1 rounded ${i === currentPage ? 'bg-brand-600 text-black' : 'bg-gray-300 hover:bg-gray-400'}`;
            pageButton.onclick = () => {
                currentPage = i;
                fetchEvents();
            };
            pagination.appendChild(pageButton);
        }
    }

    // Hiển thị chi tiết sự kiện
    function viewEvent(id) {
        fetch(`/api/events/getbyId/${id}`)
            .then(res => res.json())
            .then(event => {
                document.getElementById('viewEventTitle').innerText = event.title;
                document.getElementById('viewEventDescription').innerText = event.description;
                document.getElementById('viewEventStartDate').innerText = new Date(event.start_date).toLocaleString();
                document.getElementById('viewEventEndDate').innerText = new Date(event.end_date).toLocaleString();
                document.getElementById('viewEventImage').src = event.image ? event.image : '/default.jpg';
                document.getElementById('viewEventModal').classList.remove('hidden');
            });
    }

    // Ẩn modal chi tiết sự kiện
    function hideViewEventModal() {
        document.getElementById('viewEventModal').classList.add('hidden');
    }

    // Open Update Event Modal and pre-fill with current data
    function openUpdateEventModal(id) {
        currentEventId = id; // Set the global event ID
        fetch(`/api/events/getbyId/${id}`)
            .then(res => res.json())
            .then(event => {
                // Fill the modal with the event data
                document.getElementById('updateEventTitle').value = event.title;
                document.getElementById('updateEventDescription').value = event.description;

                // Format start and end date for datetime-local input
                const startDate = new Date(event.start_date).toISOString().slice(0, 16);
                const endDate = new Date(event.end_date).toISOString().slice(0, 16);

                document.getElementById('updateEventStartDate').value = startDate;
                document.getElementById('updateEventEndDate').value = endDate;

                // Show the current image in the preview if available
                const imagePreview = document.getElementById('updateEventImagePreview');
                if (event.image) {
                    imagePreview.src = event.image.startsWith('/') ? event.image : '/' + event.image;
                    imagePreview.classList.remove('hidden');
                }

                // Open the modal
                document.getElementById('updateEventModal').classList.remove('hidden');
            });
    }


    // Hide the update event modal
    function hideUpdateEventModal() {
        document.getElementById('updateEventModal').classList.add('hidden');
    }

    // Submit the updated event data to the server
    function submitUpdateEvent() {
        const title = document.getElementById('updateEventTitle').value;
        const description = document.getElementById('updateEventDescription').value;
        const startDate = document.getElementById('updateEventStartDate').value;
        const endDate = document.getElementById('updateEventEndDate').value;
        const imageFile = document.getElementById('updateEventImage').files[0];

        if (!title || !description || !startDate || !endDate) {
            alert('Vui lòng điền đầy đủ thông tin!');
            return;
        }

        const formData = new FormData();
        formData.append('title', title);
        formData.append('description', description);
        formData.append('start_date', startDate);
        formData.append('end_date', endDate);
        if (imageFile) formData.append('image', imageFile);

        // Use the currentEventId here
        fetch(`/api/events/update/${currentEventId}`, {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
            },
            body: formData
        })
        .then(res => {
            return res.json();
        })
        .then(data => {
            {
                fetchEvents(); // Refresh the event list
                hideUpdateEventModal(); // Hide the update modal
                showSuccessEventModal(); // Show success modal
                setTimeout(() => {
                    hideSuccessEventModal();
                }, 3000);
                resetEventForm(); // Reset the form
            } 
        })

    }

    // Hide success modal
    function hideSuccessEventModal() {
        document.getElementById('successEventModal').classList.add('hidden');
    }


    // Show success modal
    function showSuccessEventModal() {
        document.getElementById('successEventModal').classList.remove('hidden');
    }

    // Hide success modal
    function hideSuccessEventModal() {
        document.getElementById('successEventModal').classList.add('hidden');
    }

    // Handle image preview for the update form
    document.getElementById('updateEventImage').addEventListener('change', function() {
        const file = this.files[0];
        const preview = document.getElementById('updateEventImagePreview');

        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        } else {
            preview.classList.add('hidden');
        }
    });


    // Xóa sự kiện
    function deleteEvent(id) {
        if (confirm('Bạn có chắc chắn muốn xóa sự kiện này không?')) {
            fetch(`/api/events/delete/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
                }
            })
            .then(res => res.json())
            .then(() => {
                alert('Sự kiện đã được xóa');
                fetchEvents();
            })
            .catch(() => {
                alert('Lỗi khi xóa sự kiện');
            });
        }
    }
</script>
@endsection
