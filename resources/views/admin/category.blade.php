@extends('admin.dashboard')
@section('page_title', 'Quản lý danh mục')

@section('categories')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <div>
            <h2 class="text-2xl font-bold text-brand-600 dark:text-brand-400">🍽️ Danh sách danh mục</h2>
            <p class="text-gray-500 text-sm mt-1">Quản lý các nhóm món ăn theo loại như món chính, món phụ, đồ uống,...</p>
        </div>
        <button id="openAddForm"
            class="bg-gradient-to-r from-green-500 to-green-600 text-black px-5 py-2 rounded-full shadow hover:from-green-600 hover:to-green-700 transition duration-200">
            + Thêm danh mục
        </button>
    </div>

    <!-- Form thêm mới -->
    <div id="addForm" class="hidden bg-orange-50 dark:bg-gray-900 p-6 rounded-lg mb-6 border border-dashed border-brand-300">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input id="name" type="text" placeholder="🍜 Tên danh mục"
                class="w-full px-4 py-2 rounded-lg border border-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-500" />
            <span id="nameError" class="text-red-500 text-sm hidden mt-1">Vui lòng nhập tên danh mục</span>
            <input id="slug" type="number" placeholder="🔤 Số lượng"
                class="w-full px-4 py-2 rounded-lg border border-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-500" />
            <span id="slugError" class="text-red-500 text-sm hidden mt-1">Vui lòng nhập số lượng</span>
        </div>
        <div class="text-right mt-4">
            <button id="closeAddForm"
                class="bg-brand-500 text-red px-5 py-2 rounded-lg hover:bg-brand-600 transition shadow"
                style="color: red;">
                ✖ Đóng
            </button>
            <button id="submitAdd"
                class="bg-brand-500 text-black px-5 py-2 rounded-lg hover:bg-brand-600 transition shadow">
                ✅ Thêm mới
            </button>
        </div>

    </div>

    <!-- Modal Update -->
    <div id="updateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[100] hidden"
        onclick="hideUpdateModal()">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl max-w-sm w-full text-left animate-fadeIn"
            onclick="event.stopPropagation()">
            <h3 class="text-xl font-bold text-blue-600 mb-4">📝 Cập nhật danh mục</h3>
            <input id="updateName" type="text" placeholder="Đổi tên danh mục" class="w-full mb-3 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
            <input id="updateSlug" type="text" placeholder="Đổi Slug" class="w-full mb-4 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
            <div class="flex justify-end gap-2">
                <button onclick="hideUpdateModal()" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 text-black">✖ Hủy</button>
                <button onclick="submitUpdateCategory()" class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white">✅ Lưu thay đổi</button>
            </div>
        </div>
    </div>

    <!-- Danh sách danh mục -->
    <div id="category-list" class="grid sm:grid-cols-2 md:grid-cols-3 gap-6">
        <!-- JS render here -->
    </div>
    <div id="pagination" class="flex justify-center gap-2 mt-6"></div>


    <!-- Modal Xem chi tiết -->
    <div id="viewModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[100] hidden"
        onclick="hideViewModal()">
        <div class="bg-white dark:bg-gray-800 p-10 rounded-xl shadow-xl max-w-xl text-left animate-fadeIn"
            onclick="event.stopPropagation()">
            <h3 class="text-3xl font-bold text-blue-600 mb-2">👁️ Chi tiết danh mục</h3>
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-1"><strong>Tên:</strong> <span id="viewName"></span></p>
            <p class="text-lg text-gray-700 dark:text-gray-300"><strong>Số lượng:</strong> <span id="viewSlug"></span></p>
            <div class="text-right mt-4">
                <button onclick="hideViewModal()"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    ✖ Đóng
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Xác nhận xoá -->
    <div id="deleteModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[100] hidden"
        onclick="hideDeleteModal()">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl max-w-sm text-center animate-fadeIn"
            onclick="event.stopPropagation()">
            <h3 class="text-xl font-bold text-red-600 mb-2">⚠️ Xoá danh mục</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Bạn có chắc chắn muốn xoá danh mục này không?</p>
            <div class="flex justify-center gap-4">
                <button onclick="hideDeleteModal()"
                    class="bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded">
                    ✖ Hủy
                </button>
                <button onclick="confirmDeleteCategory()"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                    🗑️ Xoá
                </button>
            </div>
        </div>
    </div>


    <!-- Popup Overlay + Modal -->
    <div id="successModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[100] hidden"
        onclick="hideSuccessModal()">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl max-w-sm text-center animate-fadeIn"
            onclick="event.stopPropagation()">
            <h3 class="text-2xl font-bold text-green-600 mb-2">🎉 Thêm thành công!</h3>
            <p class="text-gray-600 dark:text-gray-300 text-sm">Danh mục mới đã được lưu vào hệ thống.</p>
            <button onclick="hideSuccessModal()"
                class="mt-4 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition">
                ✖ Đóng
            </button>
        </div>
    </div>

    <!-- Optional animation -->
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.2s ease-out;
        }
    </style>

</div>

<script>
    // Load danh mục khi trang load
    fetchCategories();
    let deleteCategoryId = null;
    let currentUpdateId = null;

    let currentPage = 1;
    const pageSize = 6;
    let categoriesData = [];
    let paginationGroup = 0; // nhóm hiện tại, mỗi nhóm 3 trang
    const pagesPerGroup = 3;





    // Toggle form
    document.getElementById('openAddForm').onclick = () => {
        document.getElementById('addForm').classList.toggle('hidden');
    };

    // Submit thêm danh mục
    document.getElementById('submitAdd').onclick = () => {
        const name = document.getElementById('name').value.trim();
        const slug = document.getElementById('slug').value.trim();

        const nameError = document.getElementById('nameError');
        const slugError = document.getElementById('slugError');

        // Reset trạng thái lỗi
        nameError.classList.add('hidden');
        slugError.classList.add('hidden');

        let hasError = false;

        if (!name) {
            nameError.classList.remove('hidden');
            hasError = true;
        }

        if (!slug) {
            slugError.classList.remove('hidden');
            hasError = true;
        }

        if (hasError) return;

        fetch('/api/categories/add', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name,
                    slug
                })
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('successModal').classList.remove('hidden');
                fetchCategories();
                document.getElementById('name').value = '';
                document.getElementById('slug').value = '';
                document.getElementById('addForm').classList.add('hidden');
            })
            .catch(err => alert('Lỗi: ' + err));
    };


    function hideSuccessModal() {
        document.getElementById('successModal').classList.add('hidden');
    }


    // // Lấy danh sách danh mục
    function fetchCategories() {
        fetch('/api/categories/getall')
            .then(res => res.json())
            .then(data => {
                categoriesData = data;
                renderPage(currentPage);
                renderPagination();
            });
    }

    function renderPage(page) {
        const list = document.getElementById('category-list');
        const start = (page - 1) * pageSize;
        const end = start + pageSize;
        const sliced = categoriesData.slice(start, end);

        list.innerHTML = sliced.map(cat => `
        <div class="p-4 bg-white dark:bg-gray-700 rounded-xl shadow border border-gray-200 dark:border-gray-600 hover:shadow-md transition relative">
            <h3 class="text-lg font-bold text-brand-600 dark:text-brand-400 mb-1">${cat.name}</h3>
            <p class="text-sm text-gray-500">Slug: <span class="font-mono">${cat.slug}</span></p>
            <div class="flex gap-2 mt-4">
                <button onclick="viewCategory(${cat.id})" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm transition">👁️ Xem</button>
                <button onclick="deleteCategory(${cat.id})" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm transition">🗑️ Xóa</button>
                <button onclick="openUpdateModal(${cat.id})" class="px-3 py-1 bg-yellow-400 text-black rounded hover:bg-yellow-500 text-sm transition">✏️ Sửa</button>
            </div>
        </div>
    `).join('');
    }

    function renderPagination() {
        const totalPages = Math.ceil(categoriesData.length / pageSize);
        const totalGroups = Math.ceil(totalPages / pagesPerGroup);
        const startPage = paginationGroup * pagesPerGroup + 1;
        const endPage = Math.min(startPage + pagesPerGroup - 1, totalPages);
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        // ← Nút lùi nhóm
        if (paginationGroup > 0) {
            const prev = document.createElement('button');
            prev.innerText = '←';
            prev.className = 'px-3 py-1 rounded bg-gray-300 hover:bg-gray-400';
            prev.onclick = () => {
                paginationGroup--;
                renderPagination();
                renderPage(currentPage);
            };
            pagination.appendChild(prev);
        }

        // Các trang trong nhóm hiện tại
        for (let i = startPage; i <= endPage; i++) {
            const btn = document.createElement('button');
            btn.innerText = i;
            btn.className = `px-3 py-1 rounded border ${i === currentPage ? 'bg-brand-600 text-black' : 'bg-gray-300 hover:bg-gray-400'}`;
            btn.onclick = () => {
                currentPage = i;
                renderPage(currentPage);
                renderPagination();
            };
            pagination.appendChild(btn);
        }

        // → Nút tới nhóm tiếp theo
        if (paginationGroup < totalGroups - 1) {
            const next = document.createElement('button');
            next.innerText = '→';
            next.className = 'px-3 py-1 rounded bg-gray-300 hover:bg-gray-400';
            next.onclick = () => {
                paginationGroup++;
                renderPagination();
                renderPage(currentPage);
            };
            pagination.appendChild(next);
        }
    }




    function viewCategory(id) {
        fetch(`/api/categories/getbyId/${id}`)
            .then(res => res.json())
            .then(data => {
                if (data && data.name) {
                    document.getElementById('viewName').innerText = data.name;
                    document.getElementById('viewSlug').innerText = data.slug;
                    document.getElementById('viewModal').classList.remove('hidden');
                } else {
                    alert('Danh mục không tồn tại!');
                }
            })
            .catch(err => alert('Lỗi khi lấy chi tiết: ' + err));
    }

    function hideViewModal() {
        document.getElementById('viewModal').classList.add('hidden');
    }


    function openUpdateModal(id) {
        fetch(`/api/categories/getbyId/${id}`)
            .then(res => res.json())
            .then(data => {
                currentUpdateId = id;
                document.getElementById('updateName').value = data.name;
                document.getElementById('updateSlug').value = data.slug;
                document.getElementById('updateModal').classList.remove('hidden');
            });
    }

    // Đóng modal cập nhật
    function hideUpdateModal() {
        document.getElementById('updateModal').classList.add('hidden');
    }

    // Đóng form thêm mới
    document.getElementById('closeAddForm').onclick = () => {
        document.getElementById('addForm').classList.add('hidden');
    };


    function submitUpdateCategory() {
        const name = document.getElementById('updateName').value;
        const slug = document.getElementById('updateSlug').value;

        fetch(`/api/categories/update/${currentUpdateId}`, {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name,
                    slug
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    fetchCategories();
                    hideUpdateModal();
                } else {
                    alert(data.message || 'Không cập nhật được danh mục.');
                }
            })
            .catch(err => alert('Lỗi khi cập nhật: ' + err));
    }

    function deleteCategory(id) {
        deleteCategoryId = id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function hideDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    function confirmDeleteCategory() {
        if (!deleteCategoryId) return;

        fetch(`/api/categories/delete/${deleteCategoryId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    fetchCategories();
                    hideDeleteModal();
                } else {
                    alert(data.message || 'Không thể xoá danh mục.');
                }
            })
            .catch(err => alert('Lỗi khi xoá: ' + err));
    }
</script>
@endsection