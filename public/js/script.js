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

    // --- Logic Search Suggestions Header ---
    const searchInputHeader = document.getElementById('search-bar');
    const suggestionsContainerHeader = document.getElementById('suggestions');

    if (searchInputHeader && suggestionsContainerHeader) {
        searchInputHeader.addEventListener('keyup', function() {
            const query = this.value.trim(); // Trim whitespace
            if (query.length < 2) {
                suggestionsContainerHeader.style.display = 'none';
                suggestionsContainerHeader.innerHTML = '';
                return;
            }
            // console.log("Header Search query:", query);

            // Sử dụng URL từ biến toàn cục `config`
            const searchUrl = config.urls.searchSuggest; // Hoặc URL API riêng nếu có
            // Thêm tham số query vào URL
            const urlWithQuery = `${searchUrl}?query=${encodeURIComponent(query)}`;

            // Kích hoạt Fetch API
            fetch(urlWithQuery, { // Sử dụng GET mặc định cho route 'search'
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest' // Thường dùng để server biết đây là AJAX
                    // CSRF thường không cần cho GET request trong 'web' middleware
                }
            })
            .then(response => {
                if (!response.ok) {
                    // Nếu lỗi 4xx, 5xx, ném lỗi để catch xử lý
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                 // Kiểm tra content type trước khi parse JSON
                 const contentType = response.headers.get('content-type');
                 if (contentType && contentType.includes('application/json')) {
                     return response.json();
                 } else {
                     // Nếu không phải JSON, có thể là trang HTML (ví dụ: lỗi server)
                     return response.text().then(text => { throw new Error('Received non-JSON response: ' + text); });
                 }
            })
            .then(data => {
                suggestionsContainerHeader.innerHTML = ""; // Clear previous
                // Giả sử data trả về là một mảng các object phim/tv/celeb
                // Cần điều chỉnh key (image_url, title, type, year, url) cho phù hợp với API response thực tế
                if (data && Array.isArray(data) && data.length > 0) {
                     data.forEach(item => {
                        const div = document.createElement("div");
                        div.classList.add("suggestion-item");
                        // Cần đảm bảo các key `item.image_url`, `item.title`, `item.type`, `item.year`, `item.url`
                        // tồn tại trong dữ liệu trả về từ SearchController::webSearch
                        div.innerHTML = `
                            <img src="${item.image_url || '/img/placeholder.jpg'}" alt="${item.title || ''}" onerror="this.src='/img/placeholder.jpg';"> {{-- Thêm fallback onerror --}}
                            <div>
                                <h4>${item.title || 'No Title'}</h4>
                                <p>${item.type || ''} ${item.year ? '(' + item.year + ')' : ''}</p> {{-- Hiển thị năm trong ngoặc --}}
                            </div>`;
                        // Gán link chuyển hướng khi click vào suggestion
                        div.onclick = () => {
                             // `item.url` cần được trả về từ SearchController
                            if (item.url) {
                                window.location.href = item.url;
                            }
                        };
                        suggestionsContainerHeader.appendChild(div);
                    });
                    suggestionsContainerHeader.style.display = "block";
                } else if (data && data.message) { // Xử lý trường hợp API trả về message (vd: "No results found")
                     suggestionsContainerHeader.innerHTML = `<div class="suggestion-item"><i>${data.message}</i></div>`;
                     suggestionsContainerHeader.style.display = "block";
                }
                 else {
                    suggestionsContainerHeader.style.display = "none"; // Ẩn nếu không có data hoặc data rỗng
                }
            })
            .catch(error => {
                console.error("Search Error:", error);
                suggestionsContainerHeader.innerHTML = `<div class="suggestion-item"><i>Error fetching suggestions.</i></div>`;
                suggestionsContainerHeader.style.display = "block"; // Hiển thị lỗi
            });

            // **XÓA BỎ** Mã hiển thị tạm thời
            // suggestionsContainerHeader.innerHTML = `<div class="suggestion-item"><i>Searching for "${query}"... (API disabled)</i></div>`;
            // suggestionsContainerHeader.style.display = "block";

        });

        // Đóng suggestions khi click ra ngoài (Giữ nguyên logic này)
        document.addEventListener('click', function(event) {
            if (suggestionsContainerHeader && searchInputHeader && !searchInputHeader.contains(event.target) && !suggestionsContainerHeader.contains(event.target)) {
                suggestionsContainerHeader.style.display = 'none';
            }
        });
    }
    
    const movieSectionsContainer = document.body; // Hoặc một container gần hơn nếu có

    movieSectionsContainer.addEventListener('click', function(event) {
        // Kiểm tra xem phần tử được click có phải là nút "Add to Watchlist" không
        const addButton = event.target.closest('.addToWatchlist-btn');
        if (addButton) {
            event.preventDefault(); // Ngăn hành vi mặc định nếu là link/button
            const movieId = addButton.dataset.movieId;
            if (movieId && typeof addToWatchlist === 'function') {
                console.log(`Add button clicked for movie ID: ${movieId}`); // Log để debug
                addToWatchlist(movieId, addButton); // Gọi hàm AJAX, truyền cả nút để cập nhật UI
            } else {
                console.error('Movie ID not found or addToWatchlist function is not defined.');
            }
            return; // Dừng xử lý thêm nếu đã khớp nút add
        }

        // Kiểm tra xem phần tử được click có phải là nút "Remove Watchlist" không
        // (Nút này xuất hiện SAU KHI thêm thành công)
        const removeButton = event.target.closest('.remove-watchlist-btn');
        if (removeButton) {
             event.preventDefault();
             const movieId = removeButton.dataset.movieId;
             if (movieId && typeof removeFromWatchlist === 'function') {
                console.log(`Remove button clicked for movie ID: ${movieId}`); // Log để debug
                // Có thể thêm confirm ở đây nếu muốn
                 if (confirm('Are you sure you want to remove this movie from your watchlist?')) {
                    removeFromWatchlist(movieId, removeButton); // Gọi hàm AJAX, truyền cả nút
                 }
            } else {
                console.error('Movie ID not found or removeFromWatchlist function is not defined.');
            }
            return; // Dừng xử lý
        }

         // === Quan trọng: Kiểm tra nút Xóa trên trang Watchlist ===
         // Nút này có class 'remove-button' trong view watchlist/index.blade.php
         const removeButtonWatchlistPage = event.target.closest('.remove-button');
         if(removeButtonWatchlistPage && removeButtonWatchlistPage.matches('[data-remove-url]')) { // Chỉ xử lý nút xóa trên trang watchlist
             event.preventDefault();
             const movieId = removeButtonWatchlistPage.dataset.movieId;
              if (movieId && typeof removeFromWatchlist === 'function') {
                  console.log(`Remove (from watchlist page) button clicked for movie ID: ${movieId}`);
                   if (confirm('Are you sure you want to remove this movie from your watchlist?')) {
                       removeFromWatchlist(movieId, null); // Không cần truyền button element nếu chỉ cần xóa item khỏi DOM
                   }
               } else {
                   console.error('Movie ID not found or removeFromWatchlist function is not defined.');
               }
               return;
         }


    });

    // --- Logic Rating Form ---
    // (Giữ nguyên phần code xử lý rating form của bạn ở đây)
    // Mở form đánh giá khi nhấn vào nút "Rate"
    document.querySelectorAll('.rate-link').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            const movieId = this.dataset.movieId;

            if (!isLoggedIn) {
                alert("Please log in to rate this movie.");
            } else {
                const form = document.querySelector(`.rating-form[data-movie-id="${movieId}"]`);
                form.style.display = 'block';

                // Kiểm tra nếu đã đánh giá, hiển thị nút "Remove Rating"
                const userRating = this.textContent.includes("★") ? parseInt(this.textContent.split("★ ")[1]) : null;
                const removeRatingBtn = form.querySelector('.remove-rating-btn');
                
                if (userRating) {
                    removeRatingBtn.style.display = 'block';
                } else {
                    removeRatingBtn.style.display = 'none';
                }
            }
        });
    });

     // Đóng form khi nhấn vào nút "X"
     document.querySelectorAll('.close-btn').forEach(closeBtn => {
        closeBtn.addEventListener('click', function() {
            this.parentElement.style.display = 'none';
        });
     });

     // Đóng form khi nhấp ra ngoài form
     window.addEventListener('click', function(event) {
        document.querySelectorAll('.rating-form').forEach(form => {
            if (form.style.display === 'block' && !form.contains(event.target) && !event.target.classList.contains('rate-link')) {
                form.style.display = 'none';
            }
        });
     });

     // Tô màu sao khi hover
     document.querySelectorAll('.rating-form').forEach(form => {
        form.querySelectorAll('.star').forEach(star => {
            star.addEventListener('click', function() {
                const movieId = form.dataset.movieId; // Lấy movie id từ form
                const ratingValue = this.dataset.value; // Lấy giá trị rating từ sao
                // Gọi hàm AJAX từ ajax.js
                if (typeof submitRating === 'function') {
                    submitRating(movieId, ratingValue);
                } else {
                    console.error('submitRating function is not defined.');
                }
            });
        });
     });

     // Sự kiện click để gửi đánh giá
     document.querySelectorAll('.star').forEach(star => {
        star.addEventListener('click', function() {
            const ratingValue = this.dataset.value;
            const movieId = this.closest('.rating-form').dataset.movieId;

            fetch('./ajax/rate_movie.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ movie_id: movieId, rating: ratingValue, user_id: loggedInUserId })
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert("Thank you for rating!");
                    updateRatingUI(movieId, ratingValue, data.new_average_rating);
                    const form = document.querySelector(`.rating-form[data-movie-id="${movieId}"]`);
                    form.style.display = 'none'; // Đóng form sau khi đánh giá thành công
                } else {
                    alert(data.error || "There was an error submitting your rating.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("There was an error submitting your rating.");
            });
        });
     });

     // Xử lý sự kiện click để xoá đánh giá
     document.querySelectorAll('.remove-rating-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Ngăn hành vi mặc định nếu là link/button trong form
             const movieId = this.dataset.movieId; // Lấy movie id từ nút
             if (confirm('Are you sure you want to remove your rating for this movie?')) {
                 // Gọi hàm AJAX từ ajax.js
                 if (typeof removeRating === 'function') {
                     removeRating(movieId);
                 } else {
                     console.error('removeRating function is not defined.');
                 }
             }
         });
     });

     // Hàm cập nhật giao diện sau khi gửi hoặc xoá đánh giá
     function updateRatingUI(movieId, ratingValue, newAverageRating) {
        console.log(`Updating UI for Movie ${movieId}: New Rating=${ratingValue}, New Avg=${newAverageRating}`);

       // Cập nhật hiển thị rating của người dùng (nếu có)
       const userRatingDisplay = document.querySelector(`.user-rating-display[data-movie-id="${movieId}"]`); // Cần có element này
       const rateLink = document.querySelector(`.rate-link[data-movie-id="${movieId}"]`);
       const removeRatingBtn = document.querySelector(`.remove-rating-btn[data-movie-id="${movieId}"]`);

       if (userRatingDisplay) {
           if (ratingValue) {
               userRatingDisplay.innerHTML = `Your Rating: ★ ${ratingValue}/10`;
               userRatingDisplay.style.display = 'inline'; // Hoặc 'block'
           } else {
               userRatingDisplay.style.display = 'none'; // Ẩn nếu xóa rating
           }
       }

       // Cập nhật nút Rate/Remove
       if (rateLink && removeRatingBtn) {
           if (ratingValue) { // Nếu có rating mới
               rateLink.style.display = 'none'; // Ẩn nút "Rate"
               removeRatingBtn.style.display = 'inline-block'; // Hiện nút "Remove Rating"
           } else { // Nếu xóa rating
               rateLink.style.display = 'inline-block'; // Hiện nút "Rate"
               removeRatingBtn.style.display = 'none'; // Ẩn nút "Remove Rating"
           }
       }

       // Cập nhật rating trung bình hiển thị trên trang
       const averageRatingDisplay = document.querySelector(`.average-rating[data-movie-id="${movieId}"]`); // Cần có element này
       if (averageRatingDisplay && typeof newAverageRating !== 'undefined' && newAverageRating !== null) {
           averageRatingDisplay.innerHTML = `★ ${parseFloat(newAverageRating).toFixed(1)}/10`;
       }

       // Reset và ẩn form rating nếu đang mở
       const form = document.querySelector(`.rating-form[data-movie-id="${movieId}"]`);
       if (form) {
           form.style.display = 'none';
           // Reset sao về trạng thái chưa chọn
           form.querySelectorAll('.star').forEach(s => s.classList.remove('selected', 'hover'));
           // Reset trạng thái hover
           form.dataset.hoverValue = "0";
       }
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