@extends('admin.dashboard')

@section('page_title', 'Quản lý người dùng')

@section('users')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <div>
            <h2 class="text-2xl font-bold text-brand-600 dark:text-brand-400">👤 Danh sách người dùng</h2>
            <p class="text-gray-500 text-sm mt-1">Quản lý thông tin người dùng, quyền truy cập và các hành động như cấm, xóa người dùng.</p>
        </div>
    </div>


    <!-- Danh sách người dùng -->
    <div id="user-list" class="grid sm:grid-cols-2 md:grid-cols-3 gap-6">
        <!-- JS render here -->
    </div>

    <!-- Modal Xem chi tiết người dùng -->
    <div id="viewUserModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[100] hidden"
        onclick="hideViewUserModal()">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl max-w-lg w-full text-left animate-fadeIn"
            onclick="event.stopPropagation()">
            <h3 class="text-2xl font-bold text-blue-600 mb-4">👤 Chi tiết người dùng</h3>

            <!-- Hiển thị tên người dùng -->
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-2"><strong>Tên người dùng:</strong>
                <span id="viewUserName" class="text-xl font-semibold"></span>
            </p>

            <!-- Hiển thị email -->
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-2"><strong>Email:</strong>
                <span id="viewUserEmail" class="text-xl font-semibold"></span>
            </p>

            <!-- Hiển thị số điện thoại -->
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-2"><strong>Số điện thoại:</strong>
                <span id="viewUserPhone" class="text-xl font-semibold"></span>
            </p>

            <!-- Hiển thị vai trò -->
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-2"><strong>Vai trò:</strong>
                <span id="viewUserRole" class="text-xl font-semibold"></span>
            </p>

            <!-- Hiển thị ngày tạo tài khoản -->
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-2"><strong>Ngày tạo:</strong>
                <span id="viewUserCreatedAt" class="text-xl font-semibold"></span>
            </p>

            <!-- Hiển thị trạng thái cấm người dùng -->
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-4"><strong>Trạng thái cấm:</strong>
                <span id="viewUserBannedUntil" class="text-xl font-semibold"></span>
            </p>

            <!-- Đóng modal -->
            <div class="text-right mt-4">
                <button onclick="hideViewUserModal()"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    ✖ Đóng
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Xác nhận Xóa -->
    <div id="deleteConfirmModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md">
            <h3 class="text-xl font-bold text-brand-600 dark:text-brand-400 mb-4">Xác nhận xóa người dùng</h3>
            <p>Bạn có chắc chắn muốn xóa người dùng này?</p>
            <div class="flex gap-4 mt-6">
                <button id="confirmDelete" class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 transition">Xóa</button>
                <button id="cancelDelete" class="bg-gray-300 text-black px-5 py-2 rounded-lg hover:bg-gray-400 transition">Hủy</button>
            </div>
        </div>
    </div>
    <!-- Pagination -->
    <div id="pagination" class="flex justify-center gap-2 mt-6"></div>
</div>

<script>
    // Fetch danh sách người dùng
    fetchUsers();
    let userIdToDelete = null; // Biến để lưu id người dùng cần xóa
    let currentPage = 1;
    const pageSize = 6;
    let usersData = [];

    // Fetch user data
    function fetchUsers() {
        fetch('/api/admin/getall', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                // Lọc bỏ Admin
                usersData = data.filter(user => user.role !== 'admin');
                renderPage(currentPage);
                renderPagination();
            })
            .catch(error => {
                console.error('Error fetching users:', error);
            });
    }

    function renderPage(page) {
        const list = document.getElementById('user-list');
        const start = (page - 1) * pageSize;
        const end = start + pageSize;
        const sliced = usersData.slice(start, end);

        list.innerHTML = sliced.map(user => `
            <div class="p-4 bg-white dark:bg-gray-700 rounded-xl shadow border border-gray-200 dark:border-gray-600 hover:shadow-md transition relative">
                <h3 class="text-lg font-bold text-brand-600 dark:text-brand-400 mb-1">${user.name}</h3>
                <p class="text-sm text-gray-500">Email: <span class="font-mono">${user.email}</span></p>
                <div class="flex gap-2 mt-4">
                    <button onclick="viewUser(${user.id})" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm transition">👁️ Xem</button>
                    <button onclick="showDeleteConfirmModal(${user.id})" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm transition">🗑️ Xóa</button>
                    <button onclick="openUpdateModal(${user.id})" class="px-3 py-1 bg-yellow-400 text-black rounded hover:bg-yellow-500 text-sm transition">✏️ Sửa</button>
                    ${user.banned_until ? `
                        <button onclick="unbanUser(${user.id})" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-sm transition">✅ Unban</button>
                    ` : `
                        <button onclick="banUser(${user.id})" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm transition">🚫 Ban</button>
                    `}
                </div>
            </div>
        `).join('');
    }

    // Ban user
    function banUser(userId) {
        fetch(`/api/admin/ban/${userId}`, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                    fetchUsers(); // Refresh danh sách người dùng sau khi ban
            })
            .catch(error => {
                console.error('Error banning user:', error);
            });
    }

    // Unban user
    function unbanUser(userId) {
        fetch(`/api/admin/unban/${userId}`, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                    fetchUsers(); // Refresh danh sách người dùng sau khi unban
            })
            .catch(error => {
                console.error('Error unbanning user:', error);
            });
    }

    // Pagination
    function renderPagination() {
        const totalPages = Math.ceil(usersData.length / pageSize);
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
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
    }

    function viewUser(id) {
        fetch(`/api/admin/getbyId/${id}`, {
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(user => {
            if (!user) {
                alert('Người dùng không tồn tại!');
                return;
            }

            // Kiểm tra và điền thông tin vào modal
            document.getElementById('viewUserName').innerText = user.name;
            document.getElementById('viewUserEmail').innerText = user.email;
            document.getElementById('viewUserPhone').innerText = user.phone_number || 'Chưa cập nhật';
            document.getElementById('viewUserRole').innerText = user.role || 'Chưa cập nhật';
            document.getElementById('viewUserCreatedAt').innerText = new Date(user.created_at).toLocaleDateString();
            document.getElementById('viewUserBannedUntil').innerText = user.banned_until ? 
                new Date(user.banned_until).toLocaleDateString() : 'Không bị cấm';

            // Hiển thị modal
            document.getElementById('viewUserModal').classList.remove('hidden');
        })
        .catch(err => {
            console.error('Lỗi khi lấy chi tiết người dùng:', err);
            alert('Lỗi khi lấy chi tiết người dùng!');
        });
    }

    // Hàm đóng modal chi tiết người dùng
    function hideViewUserModal() {
        document.getElementById('viewUserModal').classList.add('hidden');
    }



    // Hàm để hiển thị modal xác nhận xóa
    function showDeleteConfirmModal(userId) {
        userIdToDelete = userId; // Lưu id người dùng
        const modal = document.getElementById('deleteConfirmModal');
        modal.classList.remove('hidden'); // Hiển thị modal
    }

    // Hàm đóng modal
    function closeDeleteConfirmModal() {
        const modal = document.getElementById('deleteConfirmModal');
        modal.classList.add('hidden'); // Ẩn modal
    }

    // Gắn sự kiện cho các nút trong modal
    document.getElementById('confirmDelete').addEventListener('click', deleteUserConfirmed);
    document.getElementById('cancelDelete').addEventListener('click', closeDeleteConfirmModal);

    function deleteUserConfirmed() {
        fetch(`/api/admin/delete/${userIdToDelete}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    fetchUsers(); // Refresh danh sách người dùng sau khi xóa
                    closeDeleteConfirmModal(); // Đóng modal sau khi xóa
                } else {
                    alert('Không thể xóa người dùng: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error deleting user:', error);
            });
    }

    function openUpdateModal(id) {
        fetch(`/api/admin/getbyId/${id}`, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success === false) {
                    alert(data.message);
                    return;
                }

                // Tạo modal để sửa thông tin người dùng
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                modal.innerHTML = `
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md">
                    <h3 class="text-xl font-bold text-brand-600 dark:text-brand-400 mb-4">Cập nhật người dùng</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <input id="updateName" type="text" value="${data.name}" placeholder="Tên người dùng"
                            class="w-full px-4 py-2 rounded-lg border border-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-500" />
                        <input id="updateEmail" type="email" value="${data.email}" placeholder="Email người dùng"
                            class="w-full px-4 py-2 rounded-lg border border-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-500" />
                        <input id="updatePhone" type="text" value="${data.phone_number || ''}" placeholder="Số điện thoại"
                            class="w-full px-4 py-2 rounded-lg border border-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-500" />
                    </div>
                    <div class="text-right mt-4">
                        <button id="closeUpdateModal"
                            class="bg-brand-500 text-red px-5 py-2 rounded-lg hover:bg-brand-600 transition shadow"
                            style="color: red;">
                            ✖ Đóng
                        </button>
                        <button id="submitUpdateModal"
                            class="bg-brand-500 text-black px-5 py-2 rounded-lg hover:bg-brand-600 transition shadow">
                            ✅ Cập nhật
                        </button>
                    </div>
                </div>
            `;
                document.body.appendChild(modal);

                // Attach event listeners
                document.getElementById('closeUpdateModal').addEventListener('click', () => {
                    modal.remove();
                });

                document.getElementById('submitUpdateModal').addEventListener('click', () => {
                    submitUpdate(id, modal);
                });
            })
            .catch(error => {
                console.error('Error opening update modal:', error);
            });
    }

    function submitUpdate(id, modal) {
        const name = document.getElementById('updateName').value;
        const email = document.getElementById('updateEmail').value;
        const phone_number = document.getElementById('updatePhone').value;
        const role = document.getElementById('updateRole').value;

        fetch(`/api/admin/update/${id}`, {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name,
                    email,
                    phone_number,
                    role
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    fetchUsers(); // Refresh danh sách người dùng

                    // Đóng modal cập nhật
                    modal.remove();

                    // Đóng form "Thêm người dùng" nếu nó đang mở
                    const addForm = document.getElementById('addForm');
                    if (addForm && !addForm.classList.contains('hidden')) {
                        addForm.classList.add('hidden');
                    }
                } else {
                    alert('Cập nhật thất bại: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error updating user:', error);
                alert('Đã xảy ra lỗi khi cập nhật người dùng.');
            });
    }
</script>
@endsection