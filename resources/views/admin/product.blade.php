@extends('admin.dashboard')
@section('page_title', 'Quản lý sản phẩm')
@section('products')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <div>
            <h2 class="text-2xl font-bold text-brand-600 dark:text-brand-400">🛒 Danh sách sản phẩm</h2>
            <p class="text-gray-500 text-sm mt-1">Quản lý sản phẩm trong hệ thống</p>
        </div>
        <div class="flex items-center space-x-4">
            <!-- Thanh tìm kiếm -->
            <div class="relative">
                <input id="searchProduct" type="text" placeholder="🔍 Tìm sản phẩm" 
                    class="w-64 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-500" 
                    autocomplete="off">
                <!-- Phần hiển thị gợi ý -->
                <div id="suggestion-container" class="mt-2 flex flex-wrap gap-2 hidden"></div>
            </div>
            
            <!-- Nút Thêm Sản Phẩm -->
            <button id="openAddProductForm"
                class="bg-gradient-to-r from-green-500 to-green-600 text-black px-5 py-2 rounded-full shadow hover:from-green-600 hover:to-green-700 transition duration-200">
                + Thêm sản phẩm
            </button>
        </div>
    </div>


    <!-- Form thêm sản phẩm -->
    <div id="addProductForm" class="hidden bg-orange-50 dark:bg-gray-900 p-6 rounded-lg mb-6 border border-dashed border-brand-300" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input id="productName" type="text" placeholder="🍺 Tên sản phẩm"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-500" />
            <input id="productPrice" type="number" placeholder="💸 Giá(USD)"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-500" />
            <input id="productQuantity" type="number" placeholder="📦 Số lượng"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-500" />
            <div>
                <select id="productCategory"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="">📚 Chọn danh mục</option>
                    <!-- Categories will be loaded here -->
                </select>
            </div>
            <div class="col-span-2">
                <textarea id="productDesc" placeholder="🖊 Mô tả..."
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-500"></textarea>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">🖼️ Hình ảnh sản phẩm</label>
                <div class="flex items-center gap-4">
                    <input id="productImage" type="file" accept="image/*" class="hidden" />
                    <button type="button" onclick="document.getElementById('productImage').click()"
                        class="bg-gray-100 px-3 py-1 rounded border text-sm hover:bg-gray-200">
                        📁 Chọn ảnh
                    </button>
                    <img id="productImagePreview" src="" alt="Preview ảnh"
                        class="w-20 h-20 object-contain border rounded shadow hidden" />
                </div>
            </div>
        </div>
        <div class="text-right mt-4">
            <button id="closeAddProductForm"
                class="bg-brand-500 text-red px-5 py-2 rounded-lg hover:bg-brand-600 transition shadow"
                style="color: red;">
                ✖ Đóng
            </button>
            <button id="submitAddProduct"
                class="bg-brand-500 text-black px-5 py-2 rounded-lg hover:bg-brand-600 transition shadow">
                ✅ Thêm sản phẩm
            </button>
        </div>
    </div>


    <div id="product-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- JS render here -->
    </div>

    <!-- Modal Xem chi tiết sản phẩm -->
    <div id="viewProductModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[100] hidden"
        onclick="hideViewProductModal()">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl max-w-lg w-full text-left animate-fadeIn"
            onclick="event.stopPropagation()">
            <h3 class="text-2xl font-bold text-blue-600 mb-2">👁️ Chi tiết sản phẩm</h3>
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-1"><strong>Tên sản phẩm:</strong> <span id="viewProductName"></span></p>
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-1"><strong>Giá:</strong> <span id="viewProductPrice"></span></p>
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-1"><strong>Số lượng:</strong> <span id="viewProductQuantity"></span></p>
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-1"><strong>Danh mục:</strong> <span id="viewProductCategory"></span></p>
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-1"><strong>Mô tả:</strong></p>
            <div id="viewProductDesc" class="mb-4">
                <span id="viewProductDescContent"></span>
            </div>
            <div class="text-center mt-4">
                <img id="viewProductImage" src="" alt="Ảnh sản phẩm"
                    class="max-w-full max-h-60 object-contain border rounded shadow">
            </div>
            <div class="text-right mt-4">
                <button onclick="hideViewProductModal()"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    ✖ Đóng
                </button>
            </div>
        </div>
    </div>





    <div id="updateProductModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[100] hidden" onclick="hideUpdateProductModal()">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl max-w-sm w-full text-left animate-fadeIn" onclick="event.stopPropagation()">
            <h3 class="text-xl font-bold text-blue-600 mb-4">📝 Cập nhật sản phẩm</h3>
            <input id="updateProductName" type="text" placeholder="Tên sản phẩm" class="w-full mb-3 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
            <input id="updateProductPrice" type="number" placeholder="Giá(USD)" class="w-full mb-3 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
            <input id="updateProductQuantity" type="number" placeholder="Số lượng" class="w-full mb-3 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
            <select id="updateProductCategory" class="w-full mb-3 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500"></select>
            <textarea id="updateProductDesc" placeholder="Mô tả" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 mb-3"></textarea>
            <div class="flex items-center gap-4">
                <input id="updateProductImage" type="file" accept="image/*" class="hidden"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500" />
                <button type="button" onclick="document.getElementById('updateProductImage').click()"
                    class="bg-gray-100 px-3 py-1 rounded border text-sm hover:bg-gray-200">
                    📁 Chọn ảnh
                </button>
                <img id="currentProductImagePreview" src="" alt="Ảnh hiện tại"
                    class="w-20 h-20 object-contain border rounded shadow" />
            </div>
            <span id="updateProductImageName" class="text-sm text-gray-500 block mt-1 ml-1"></span>
            <div class="flex justify-end gap-2">
                <button onclick="hideUpdateProductModal()" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 text-black">✖ Hủy</button>
                <button onclick="submitUpdateProduct()" class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white">✅ Lưu</button>
            </div>
        </div>
    </div>

    <!-- Modal Xác nhận xoá sản phẩm -->
    <div id="deleteProductModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[100] hidden"
        onclick="hideDeleteProductModal()">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl max-w-sm text-center animate-fadeIn"
            onclick="event.stopPropagation()">
            <h3 class="text-xl font-bold text-red-600 mb-2">⚠️ Xoá sản phẩm</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Bạn có chắc chắn muốn xoá sản phẩm này không?</p>
            <div class="flex justify-center gap-4">
                <button onclick="hideDeleteProductModal()"
                    class="bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded">
                    ✖ Hủy
                </button>
                <button onclick="confirmDeleteProduct()"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                    🗑️ Xoá
                </button>
            </div>
        </div>
    </div>

    <div id="pagination" class="flex justify-center gap-2 mt-6">
        <!-- Phân trang sẽ được render tại đây -->
    </div>



    <!-- Success Modal -->
    <div id="successProductModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[100] hidden">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl max-w-sm text-center animate-fadeIn">
            <h3 class="text-2xl font-bold text-green-600 mb-2">🎉 Thêm thành công!</h3>
            <p class="text-gray-600 dark:text-gray-300 text-sm">Sản phẩm mới đã được thêm vào hệ thống.</p>
            <button onclick="hideSuccessProductModal()"
                class="mt-4 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition">
                ✖ Đóng
            </button>
        </div>
    </div>
</div>

<script>
    let currentUpdateProductId = null;
    let deleteProductId = null;
    let currentPage = 1;
    const pageSize = 6;  // 6 sản phẩm mỗi trang


    // Load categories when page loads
    document.addEventListener('DOMContentLoaded', function() {
        fetchCategories();
        fetchProducts();
    });

    document.getElementById('openAddProductForm').onclick = () => {
        document.getElementById('addProductForm').classList.toggle('hidden');
    };

    // Đóng form thêm sản phẩm
    document.getElementById('closeAddProductForm').onclick = () => {
        document.getElementById('addProductForm').classList.add('hidden');
    };

    function showSuccessProductModal() {
        document.getElementById('successProductModal').classList.remove('hidden');
    }

    function hideSuccessProductModal() {
        document.getElementById('successProductModal').classList.add('hidden');
    }

    // Function to fetch categories
    function fetchCategories() {
        fetch('/api/categories/getall')
            .then(res => res.json())
            .then(categories => {
                const select = document.getElementById('productCategory');
                categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    select.appendChild(option);
                });
            });
    }

    document.getElementById('productImage').addEventListener('change', function () {
        const file = this.files[0];
        const fileName = document.getElementById('productImageName');
        const preview = document.getElementById('productImagePreview');

        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        } else {
            fileName.textContent = '';
            preview.classList.add('hidden');
        }
    });


    document.getElementById('submitAddProduct').onclick = async () => {
        const name = document.getElementById('productName').value;
        const price = parseFloat(document.getElementById('productPrice').value);
        const quantity = parseInt(document.getElementById('productQuantity').value);
        const description = document.getElementById('productDesc').value;
        const category_id = parseInt(document.getElementById('productCategory').value);
        const imageFile = document.getElementById('productImage').files[0];

        if (!name || isNaN(price) || isNaN(quantity) || isNaN(category_id)) {
            alert('Vui lòng điền đầy đủ thông tin bắt buộc (Tên, Giá, Số lượng, Danh mục)');
            return;
        }

        const formData = new FormData();
        formData.append('name', name);
        formData.append('price', price);
        formData.append('quantity', quantity);
        formData.append('description', description);
        formData.append('category_id', category_id);
        if (imageFile) {
            formData.append('imageFile', imageFile);
        }

        const response = await fetch('/api/products/add', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
            },
            body: formData
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Lỗi không xác định từ server');
        }

        // Show success modal
        showSuccessProductModal();

        // Reset the form after successful product addition
        resetForm();

        // Fetch products again to display the newly added one
        fetchProducts();
    };

    // Function to reset the form
    function resetForm() {
        document.getElementById('productName').value = '';
        document.getElementById('productPrice').value = '';
        document.getElementById('productQuantity').value = '';
        document.getElementById('productDesc').value = '';
        document.getElementById('productCategory').selectedIndex = 0;  // Reset category dropdown

        // Reset file input and hide preview
        document.getElementById('productImage').value = '';  // Clear file input
        document.getElementById('productImagePreview').classList.add('hidden'); // Hide the image preview
    }



    function fetchProducts() {
        fetch(`/api/products/getall?pageNumber=${currentPage}`)
            .then(res => res.json())
            .then(data => {
                const list = document.getElementById('product-list');
                list.innerHTML = data.products.map(product => {
                    const priceUSD = product.price;
                    return`
                    <div class="p-4 border rounded shadow bg-white dark:bg-gray-700">
                        <h3 class="font-bold text-brand-600 dark:text-brand-400">${product.name}</h3>
                        <p class="text-sm text-gray-500">💵 Giá: $${Number(priceUSD).toLocaleString()}</p>
                        <p class="text-sm text-gray-500">Số lượng: ${product.quantity}</p>
                        <p class="text-sm text-gray-500">Danh mục: ${product.category?.name || 'Không có'}</p>
                        <p class="text-sm text-gray-400 product-description">
                            ${product.description.length > 10 ? 
                                product.description.slice(0, 50) + '... <a href="javascript:void(0);" onclick="viewProduct(' + product.id + ')">Xem thêm</a>' : 
                                product.description}
                        </p>

                        <div class="flex justify-center">
                            <img src="${product.image_url?.startsWith('/') ? product.image_url : '/' + product.image_url}"
                                alt="${product.name}" class="max-w-sm max-h-48 object-contain rounded mb-3">
                        </div>
                        <div class="mt-2 flex gap-2">
                            <button onclick="viewProduct(${product.id})" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm transition">
                                👁 Xem
                            </button>
                            <button onclick="deleteProduct(${product.id})" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm transition">
                                🗑 Xóa
                            </button>
                            <button onclick="openUpdateProductModal(${product.id})" class="px-3 py-1 bg-yellow-400 text-black rounded hover:bg-yellow-500 text-sm transition">
                                ✏️ Sửa
                            </button>
                        </div>
                    </div>
                `}).join('');
                renderPagination(data.total_pages);
            });
    }


    // Hàm render phân trang
    function renderPagination(totalPages) {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';  // Xóa các nút phân trang cũ

        // Nút lùi trang
        if (currentPage > 1) {
            const prevButton = document.createElement('button');
            prevButton.innerText = '←';
            prevButton.className = 'px-3 py-1 rounded bg-gray-300 hover:bg-gray-400';
            prevButton.onclick = () => {
                currentPage--;
                fetchProducts();
            };
            pagination.appendChild(prevButton);
        }

        // Các nút trang
        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('button');
            pageButton.innerText = i;
            pageButton.className = `px-3 py-1 rounded ${i === currentPage ? 'bg-brand-600 text-black' : 'bg-gray-300 hover:bg-gray-400'}`;
            pageButton.onclick = () => {
                currentPage = i;
                fetchProducts();
            };
            pagination.appendChild(pageButton);
        }

        // Nút tiến trang
        if (currentPage < totalPages) {
            const nextButton = document.createElement('button');
            nextButton.innerText = '→';
            nextButton.className = 'px-3 py-1 rounded bg-gray-300 hover:bg-gray-400';
            nextButton.onclick = () => {
                currentPage++;
                fetchProducts();
            };
            pagination.appendChild(nextButton);
        }
    }


    // Hàm hiển thị chi tiết sản phẩm khi nhấn vào "Xem thêm"
    function viewProduct(id) {
        fetch(`/api/products/getproductbyid/${id}`)
            .then(res => res.json())
            .then(product => {
                if (!product) {
                    alert('Sản phẩm không tồn tại!');
                    return;
                }
                // Kiểm tra xem có thông tin trả về đầy đủ không
                if (product.name && product.price && product.quantity) {
                    document.getElementById('viewProductName').innerText = product.name;
                    const priceUSD = product.price;
                    document.getElementById('viewProductPrice').innerText = `$${Number(priceUSD).toLocaleString()}`;
                    document.getElementById('viewProductQuantity').innerText = product.quantity;
                    document.getElementById('viewProductCategory').innerText = product.category?.name || 'Không có';
                    document.getElementById('viewProductDescContent').innerText = product.description || 'Chưa có mô tả';
                    const imageUrl = product.image_url?.startsWith('/') ? product.image_url : '/' + product.image_url;
                    document.getElementById('viewProductImage').src = imageUrl;
                    document.getElementById('viewProductModal').classList.remove('hidden');
                } else {
                    alert('Dữ liệu sản phẩm không đầy đủ!');
                }
            })
            .catch(err => {
                console.error('Lỗi khi lấy chi tiết sản phẩm:', err);
                alert('Lỗi khi lấy chi tiết sản phẩm!');
            });
    }





    // Hàm đóng modal chi tiết sản phẩm
    function hideViewProductModal() {
        document.getElementById('viewProductModal').classList.add('hidden');
    }


    function openUpdateProductModal(id) {
        fetch(`/api/products/getproductbyid/${id}`)
            .then(res => res.json())
            .then(product => {
                currentUpdateProductId = id;
                
                // Reset file input và preview
                const fileInput = document.getElementById('updateProductImage');
                fileInput.value = ''; // Reset file input
                document.getElementById('updateProductImageName').textContent = '';
                
                // Đặt giá trị các trường
                document.getElementById('updateProductName').value = product.name;
                document.getElementById('updateProductPrice').value = product.price;
                document.getElementById('updateProductQuantity').value = product.quantity;
                document.getElementById('updateProductDesc').value = product.description || '';
                
                const imagePreview = document.getElementById('currentProductImagePreview');
                if (product.image_url) {
                    imagePreview.src = product.image_url.startsWith('/') ? product.image_url : '/' + product.image_url;
                    imagePreview.style.display = 'block';
                } else {
                    imagePreview.style.display = 'none';
                }

                // Load categories
                const catSelect = document.getElementById('updateProductCategory');
                catSelect.innerHTML = '';
                fetch('/api/categories/getall')
                    .then(res => res.json())
                    .then(categories => {
                        categories.forEach(category => {
                            const option = document.createElement('option');
                            option.value = category.id;
                            option.textContent = category.name;
                            if (category.id === product.category_id) option.selected = true;
                            catSelect.appendChild(option);
                        });
                    });

                document.getElementById('updateProductModal').classList.remove('hidden');
            });
    }

    function hideUpdateProductModal() {
            document.getElementById('updateProductImage').value = '';
            document.getElementById('updateProductModal').classList.add('hidden');
        }

        document.getElementById('updateProductImage').addEventListener('change', function () {
        const file = this.files[0];
        const fileName = document.getElementById('updateProductImageName');
        if (file) {
            // Cập nhật preview nếu cần
            const preview = document.getElementById('currentProductImagePreview');
            preview.src = URL.createObjectURL(file);
        } else {
            fileName.textContent = '';
        }
    });


    function submitUpdateProduct() {
        const formData = new FormData();
        formData.append('name', document.getElementById('updateProductName').value);
        formData.append('price', document.getElementById('updateProductPrice').value);
        formData.append('quantity', document.getElementById('updateProductQuantity').value);
        formData.append('description', document.getElementById('updateProductDesc').value);
        formData.append('category_id', document.getElementById('updateProductCategory').value);

        // Chỉ thêm imageFile nếu người dùng đã chọn file mới
        const imageFile = document.getElementById('updateProductImage').files[0];
        if (imageFile) {
            formData.append('imageFile', imageFile);
        }

        fetch(`/api/products/update/${currentUpdateProductId}`, {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('access_token')
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                fetchProducts();
                hideUpdateProductModal();
            } else {
                alert(data.message || 'Không cập nhật được sản phẩm');
            }
        })
        .catch(err => alert('Lỗi khi cập nhật: ' + err));
    }

    // Hàm hiển thị modal xác nhận xóa sản phẩm
    function deleteProduct(id) {
        deleteProductId = id;
        document.getElementById('deleteProductModal').classList.remove('hidden');
    }

    // Hàm đóng modal xóa sản phẩm
    function hideDeleteProductModal() {
        document.getElementById('deleteProductModal').classList.add('hidden');
    }

    // Hàm xác nhận xóa sản phẩm
    function confirmDeleteProduct() {
        if (!deleteProductId) return;

        fetch(`/api/products/delete/${deleteProductId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    fetchProducts(); // Refresh the product list
                    hideDeleteProductModal();
                } else {
                    alert(data.message || 'Không thể xoá sản phẩm.');
                }
            })
            .catch(err => alert('Lỗi khi xoá: ' + err));
    }
</script>
<!-- Hàm tìm kiếm sản phẩm -->
<script>
// Lắng nghe sự kiện nhập liệu trên thanh tìm kiếm
document.getElementById('searchProduct').addEventListener('input', function() {
    const query = this.value.trim();

    if (query.length > 0) {
        fetchSuggestions(query);
    } else {
        clearSuggestions();
        fetchProducts(); // Tải lại danh sách sản phẩm mặc định khi không có query
    }
});

// Gửi yêu cầu API tìm kiếm và hiển thị kết quả gợi ý
function fetchSuggestions(query) {
    fetch(`/api/suggest?term=${query}`)
        .then(res => res.json())
        .then(suggestions => {
            if (suggestions.length > 0) {
                showSuggestions(suggestions);
            } else {
                clearSuggestions();
            }
        })
        .catch(err => {
            console.error('Error fetching suggestions:', err);
        });
}

// Hiển thị danh sách các gợi ý tên sản phẩm dưới dạng danh sách dọc
function showSuggestions(suggestions) {
    const suggestionContainer = document.getElementById('suggestion-container');
    suggestionContainer.classList.remove('hidden');  // Hiển thị container gợi ý

    // Hiển thị các gợi ý dưới dạng danh sách dọc
    suggestionContainer.innerHTML = `
        <ul class="bg-white border border-gray-300 shadow-lg rounded-lg w-64">
            ${suggestions.map(suggestion => `
                <li class="suggestion-item px-4 py-2 text-gray-800 hover:bg-gray-100 cursor-pointer"
                    onclick="selectSuggestion('${suggestion}')">
                    ${suggestion}
                </li>
            `).join('')}
        </ul>
    `;
}

// Chọn một gợi ý và thực hiện tìm kiếm
function selectSuggestion(suggestion) {
    document.getElementById('searchProduct').value = suggestion;
    searchProducts(suggestion); // Thực hiện tìm kiếm khi chọn một gợi ý
    clearSuggestions(); // Ẩn danh sách gợi ý
}

// Thực hiện tìm kiếm sản phẩm
function searchProducts(query) {
    fetch(`/api/search?query=${query}`)
        .then(res => res.json())
        .then(products => {
            displayProducts(products); // Hiển thị các sản phẩm tìm được
        })
        .catch(err => {
            console.error('Error fetching products:', err);
        });
}

// Hiển thị danh sách sản phẩm tìm được
function displayProducts(products) {
    const productList = document.getElementById('product-list');
    productList.innerHTML = products.map(product =>{
        const priceUSD = product.price;
        return `
        <div class="p-4 border rounded shadow bg-white dark:bg-gray-700">
            <h3 class="font-bold text-brand-600 dark:text-brand-400">${product.name}</h3>
            <p class="text-sm text-gray-500">💵 Giá: $${Number(priceUSD).toLocaleString()}</p>
            <p class="text-sm text-gray-500">Số lượng: ${product.quantity}</p>
            <p class="text-sm text-gray-500">Danh mục: ${product.category?.name || 'Không có'}</p>
            <div class="flex justify-center">
                <img src="${product.image_url?.startsWith('/') ? product.image_url : '/' + product.image_url}"
                    alt="${product.name}" class="max-w-sm max-h-48 object-contain rounded mb-3">
            </div>
            <div class="mt-2 flex gap-2">
                <button onclick="viewProduct(${product.id})" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm transition">
                    👁 Xem
                </button>
                <button onclick="deleteProduct(${product.id})" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm transition">
                    🗑 Xóa
                </button>
                <button onclick="openUpdateProductModal(${product.id})" class="px-3 py-1 bg-yellow-400 text-black rounded hover:bg-yellow-500 text-sm transition">
                    ✏️ Sửa
                </button>
            </div>
        </div>
    `}).join('');
}

// Xóa các gợi ý khi người dùng không nhập gì
function clearSuggestions() {
    const suggestionContainer = document.getElementById('suggestion-container');
    if (suggestionContainer) {
        suggestionContainer.innerHTML = '';
        suggestionContainer.classList.add('hidden');
    }
}
</script>
@endsection