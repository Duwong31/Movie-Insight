document.addEventListener("DOMContentLoaded", function() {

    // --- Logic Slider ---
    const productContainers = [...document.querySelectorAll('.movie-container')];
    const nxtBtn = [...document.querySelectorAll('.nxt-btn')];
    const preBtn = [...document.querySelectorAll('.pre-btn')];

    productContainers.forEach((item, i) => {
        let containerDimensions = item.getBoundingClientRect();
        let containerWidth = containerDimensions.width;

        if (nxtBtn[i]) { // Kiểm tra nút tồn tại
            nxtBtn[i].addEventListener('click', () => {
                item.scrollLeft += containerWidth;
            });
        }

        if (preBtn[i]) { // Kiểm tra nút tồn tại
            preBtn[i].addEventListener('click', () => {
                item.scrollLeft -= containerWidth;
            });
        }
    });

    // --- Logic các nút Header (Login, Signup, News, Watchlist) ---
    const loginBtn = document.getElementById("login-btn");
    const signUpBtn = document.getElementById("sign-up-btn");
    const newsBtn = document.getElementById("news-btn");
    const watchlistBtn = document.getElementById('watchlist-btn'); // Lấy nút watchlist

    if (loginBtn) {
        loginBtn.addEventListener("click", function() {
            // Chú ý: Dùng URL thật thay vì query string nếu dùng Laravel routing
            // window.location.href = "{{ url('/login') }}"; // Ví dụ với Laravel route
            window.location.href = "?module=auth&action=loginUser"; // Mã gốc của bạn
        });
    }

    if (signUpBtn) {
        signUpBtn.addEventListener("click", function() {
            // window.location.href = "{{ url('/register') }}"; // Ví dụ với Laravel route
            window.location.href = "?module=auth&action=signup"; // Mã gốc của bạn
        });
    }

    if (newsBtn) {
        newsBtn.addEventListener("click", function() {
            // window.location.href = "{{ url('/news') }}"; // Ví dụ với Laravel route
            window.location.href = "?module=view&action=news"; // Mã gốc của bạn
        });
    }

    // *** DI CHUYỂN TỪ HEADER.BLADE.PHP ***
    if (watchlistBtn) {
        watchlistBtn.addEventListener('click', function() {
            // Khi có logic auth, bạn sẽ kiểm tra ở đây
            // Ví dụ: Giả sử có biến isLoggedIn từ backend (cần truyền vào JS)
            // if (typeof isLoggedIn !== 'undefined' && isLoggedIn) {
            //     window.location.href = '/watchlist'; // URL thật
            // } else {
            //     alert('Watchlist feature requires login.');
            //     // Hoặc chuyển hướng đến trang login
            //     // window.location.href = '/login'; // URL thật
            // }

            // Mã tạm thời như trong file blade cũ
            alert('Watchlist feature requires login (coming soon).');
        });
    }

    // --- Logic Search Suggestions Header ---
    // *** DI CHUYỂN TỪ HEADER.BLADE.PHP ***
    const searchInputHeader = document.getElementById('search-bar');
    const suggestionsContainerHeader = document.getElementById('suggestions');

    if (searchInputHeader && suggestionsContainerHeader) {
        searchInputHeader.addEventListener('keyup', function() {
            const query = this.value;
            if (query.length < 2) {
                suggestionsContainerHeader.style.display = 'none';
                suggestionsContainerHeader.innerHTML = '';
                return;
            }
            console.log("Header Search query:", query); // Log để biết đang gõ

            // *** Chú ý: Phần Fetch API đang bị comment out ***
            // Bạn cần kích hoạt lại và thay đổi URL thành API thật của Laravel
            /*
            const searchUrl = '/api/search'; // <<< THAY BẰNG API ROUTE THẬT CỦA BẠN

            fetch(`${searchUrl}?query=${encodeURIComponent(query)}`, {
                headers: {
                    'Accept': 'application/json',
                    // Có thể cần thêm CSRF token nếu API của bạn yêu cầu
                    // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                 }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                suggestionsContainerHeader.innerHTML = ""; // Clear previous
                if (data && data.length > 0) {
                     data.forEach(item => {
                        const div = document.createElement("div");
                        div.classList.add("suggestion-item");
                        // Cập nhật innerHTML để phù hợp với dữ liệu trả về từ API
                        div.innerHTML = `
                            <img src="${item.image_url || '/img/placeholder.jpg'}" alt="${item.title || ''}">
                            <div>
                                <h4>${item.title || 'No Title'}</h4>
                                <p>${item.type || ''} ${item.year || ''}</p>
                            </div>`;
                        // Cập nhật URL khi click
                        div.onclick = () => {
                            window.location.href = item.url || '#'; // URL chi tiết
                        };
                        suggestionsContainerHeader.appendChild(div);
                    });
                    suggestionsContainerHeader.style.display = "block";
                } else {
                    suggestionsContainerHeader.style.display = "none";
                }
            })
            .catch(error => {
                console.error("Search Error:", error);
                suggestionsContainerHeader.innerHTML = `<div class="suggestion-item"><i>Error fetching suggestions.</i></div>`;
                suggestionsContainerHeader.style.display = "block"; // Hiển thị lỗi
            });
            */

            // Mã hiển thị tạm thời (bỏ đi khi kích hoạt fetch)
            suggestionsContainerHeader.innerHTML = `<div class="suggestion-item"><i>Searching for "${query}"... (API disabled)</i></div>`;
            suggestionsContainerHeader.style.display = "block";

        });

        // Đóng suggestions khi click ra ngoài
        document.addEventListener('click', function(event) {
            // Kiểm tra suggestionsContainerHeader có tồn tại không trước khi truy cập thuộc tính
            if (suggestionsContainerHeader && searchInputHeader && !searchInputHeader.contains(event.target) && !suggestionsContainerHeader.contains(event.target)) {
                suggestionsContainerHeader.style.display = 'none';
            }
        });
    }


    // --- Logic Search cũ (có thể không cần nữa nếu search header hoạt động) ---
    /* <<< COMMENT OUT HOẶC XÓA HÀM NÀY ĐỂ TRÁNH XUNG ĐỘT >>>
    function searchMovies(query) {
        const suggestions = document.getElementById("suggestions"); // Dùng chung ID với search header?
        if (query.length === 0) {
            suggestions.style.display = "none";
            return;
        }

        // URL này dùng cấu trúc PHP cũ, không phải Laravel API
        fetch(`./modules/home/search.php?query=${query}`)
            .then((response) => response.json())
            .then((data) => {
                suggestions.innerHTML = ""; // Clear previous suggestions
                data.forEach((movie) => {
                    // ... (logic tạo item giống mã gốc) ...
                });
                suggestions.style.display = "block";
            })
            .catch((error) => console.error("Error:", error));
    }
    */

    // --- Logic Rating Form ---
    // (Giữ nguyên phần code xử lý rating form của bạn ở đây)
    // Mở form đánh giá khi nhấn vào nút "Rate"
    document.querySelectorAll('.rate-link').forEach(link => {
         // ... (code gốc của bạn) ...
    });

     // Đóng form khi nhấn vào nút "X"
     document.querySelectorAll('.close-btn').forEach(closeBtn => {
         // ... (code gốc của bạn) ...
     });

     // Đóng form khi nhấp ra ngoài form
     window.addEventListener('click', function(event) {
         // ... (code gốc của bạn) ...
     });

     // Tô màu sao khi hover
     document.querySelectorAll('.rating-form').forEach(form => {
         // ... (code gốc của bạn) ...
     });

     // Sự kiện click để gửi đánh giá
     document.querySelectorAll('.star').forEach(star => {
         // ... (code gốc của bạn) ...
     });

     // Xử lý sự kiện click để xoá đánh giá
     document.querySelectorAll('.remove-rating-btn').forEach(button => {
         // ... (code gốc của bạn) ...
     });

     // Hàm cập nhật giao diện sau khi gửi hoặc xoá đánh giá
     function updateRatingUI(movieId, ratingValue, newAverageRating) {
         // ... (code gốc của bạn) ...
     }
     // (Kết thúc phần code rating)

     // Header - Profile Dropdown
    const dropdownToggle = document.getElementById('user-dropdown-toggle');
    const dropdownMenu = document.getElementById('user-dropdown-menu');
    const dropdownContainer = document.getElementById('user-dropdown-container'); // Lấy container

    console.log("Dropdown Toggle Element:", dropdownToggle);
    console.log("Dropdown Menu Element:", dropdownMenu);
    console.log("Dropdown Container Element:", dropdownContainer);

    if (dropdownToggle && dropdownMenu && dropdownContainer) {
        console.log("Dropdown elements FOUND. Adding click listener to toggle.");
        dropdownToggle.addEventListener('click', function(event) {
            event.preventDefault(); // Ngăn link điều hướng
            console.log("Dropdown TOGGLE CLICKED!");
            dropdownMenu.classList.toggle('show');
            console.log("Dropdown menu classes:", dropdownMenu.classList);
        });
        console.log("Adding document click listener for outside clicks.");
        // Đóng dropdown khi click ra bên ngoài
        document.addEventListener('click', function(event) {
            // Quan trọng: Chỉ đóng nếu click nằm ngoài TOÀN BỘ container dropdown
            if (!dropdownContainer.contains(event.target)) {
                // Chỉ thực hiện remove class nếu menu đang hiển thị
                if (dropdownMenu.classList.contains('show')) {
                    console.log("Clicked OUTSIDE dropdown container. Closing menu."); // Xác nhận click ngoài và đóng
                    dropdownMenu.classList.remove('show');
                    console.log("Dropdown menu classes after closing:", dropdownMenu.classList);
                }
            } else {
                // Optional: Ghi log khi click bên trong để dễ debug
                // console.log("Clicked INSIDE dropdown container.");
            }
        });
    }else {
        // Thông báo lỗi nếu không tìm thấy phần tử nào đó
        console.error("ERROR: Could not find one or more dropdown elements! Check IDs in HTML and JS.");
    }
}); // Kết thúc listener DOMContentLoaded chính