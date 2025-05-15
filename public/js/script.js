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
        searchInputHeader.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Ngăn form submit mặc định nếu không muốn
                const query = this.value.trim();
                if (query.length > 0) {
                    // Chuyển hướng đến trang kết quả tìm kiếm đầy đủ
                    window.location.href = `${config.urls.searchSuggest}?query=${encodeURIComponent(query)}`;
                    // Hoặc nếu action của form đã đúng, có thể gọi:
                    // this.closest('form').submit();
                }
            }
        });
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
                     console.log("Response is JSON, parsing...");
                     return response.json();
                 } else {
                    console.error('Received non-JSON response. Content-Type:', contentType);
                     return response.text().then(text => { throw new Error('Received non-JSON response: ' + text); });
                 }
            })
            .then(data => {
                console.log("Data received by JS for suggestions:", data);
                suggestionsContainerHeader.innerHTML = ""; // Clear previous
                // Giả sử data trả về là một mảng các object phim/tv/celeb
                // Cần điều chỉnh key (image_url, title, type, year, url) cho phù hợp với API response thực tế
                if (data && Array.isArray(data) && data.length > 0) {
                    console.log("Data is an array and has items. Processing each...");
                     data.forEach(item => {
                        console.log("Processing suggestion item:", item); 
                        const div = document.createElement("div");
                        div.classList.add("suggestion-item");
                        // Cần đảm bảo các key `item.image_url`, `item.title`, `item.type`, `item.year`, `item.url`
                        // tồn tại trong dữ liệu trả về từ SearchController::webSearch
                        div.innerHTML = `
                            <img src="${item.image_url || '/img/placeholder.jpg'}" alt="${item.title || ''}" onerror="this.src='/img/placeholder.jpg';">
                            <div>
                                <h4>${item.title || 'No Title'}</h4>
                                <p>${item.type || ''} ${item.year ? '(' + item.year + ')' : ''}</p>
                            </div>`;
                        // Gán link chuyển hướng khi click vào suggestion
                        div.onclick = () => {
                             // `item.url` cần được trả về từ SearchController
                            if (item.url) {
                                window.location.href = item.url;
                            }
                        };
                        suggestionsContainerHeader.appendChild(div);
                        console.log("Item details for HTML: ", item.image_url, item.title, item.type, item.year, item.url);
                    });
                    suggestionsContainerHeader.style.display = "block";
                    console.log("Suggestions container display set to block.");
                } else if (data && data.message) {
                    console.log("Data has a message:", data.message);
                     suggestionsContainerHeader.innerHTML = `<div class="suggestion-item"><i>${data.message}</i></div>`;
                     suggestionsContainerHeader.style.display = "block";
                }
                 else {
                    console.log("No valid data or message to display. Hiding suggestions.");
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

    function updateStarsAndDisplay(targetForm, currentValue, isHovering = false) {
        // Kiểm tra xem targetForm có hợp lệ không
        if (!targetForm) {
            // console.error("updateStarsAndDisplay called with invalid targetForm");
            return;
        }
        const localStars = targetForm.querySelectorAll('.star');
        const localValueDisplay = targetForm.querySelector('.rating-value-display');
        const selectedVal = parseInt(targetForm.dataset.selectedValue || '0');

        // console.log(`Updating display for form ${targetForm.dataset.movieId}. Value: ${currentValue}, Hover: ${isHovering}, Selected: ${selectedVal}`);
        // console.log("Found localValueDisplay:", localValueDisplay);

        if (localValueDisplay) {
            if (isHovering) {
                localValueDisplay.textContent = currentValue > 0 ? currentValue : '?';
            } else {
                localValueDisplay.textContent = selectedVal > 0 ? selectedVal : '?';
            }
        } else {
             // console.error("Could not find .rating-value-display inside the form:", targetForm);
        }

        localStars.forEach((s, index) => {
            const starValue = index + 1;
            s.classList.remove('hover', 'selected', 'fas', 'far');
            const compareValue = isHovering ? currentValue : selectedVal;
            if (starValue <= compareValue) {
                s.classList.add('fas');
                if (isHovering && starValue <= currentValue) {
                    s.classList.add('hover');
                }
                if (!isHovering && starValue <= selectedVal) {
                    s.classList.add('selected');
                }
            } else {
                s.classList.add('far');
            }
        });
    }

    // --- Logic Rating Form ---
    document.querySelectorAll('.rate-link').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();

            const movieId = this.dataset.movieId;
            console.log("Clicked Rate link for movie ID:", movieId); // Log 1
            if (!movieId) {
                console.error("Movie ID is missing on the clicked rate link:", this);
                return;
            }

            if (!config.isLoggedIn) {
                alert("Please log in to rate this movie.");
                window.location.href = `${config.urls.login}?redirect=${encodeURIComponent(window.location.href)}`;
                return;
            }

            const formSelector = `.rating-form[data-movie-id="${movieId}"]`;
            console.log("Looking for form with selector:", formSelector); // Log 2
            const form = document.querySelector(formSelector);
            console.log("Found form element:", form); // Log 3

            if (form) {
                // Đóng các form khác
                document.querySelectorAll('.rating-form').forEach(otherForm => {
                    if (otherForm !== form && otherForm.style.display !== 'none') {
                        otherForm.style.display = 'none';
                    }
                });

                // Reset form và cập nhật UI ban đầu
                const submitButton = form.querySelector('.btn-submit-rating');
                const currentRating = parseInt(form.dataset.currentRating || '0');
                const removeRatingBtn = form.querySelector('.remove-rating-btn');

                form.dataset.selectedValue = currentRating;

                // <<< GỌI HÀM TỪ PHẠM VI MỚI >>>
                updateStarsAndDisplay(form, currentRating, false); // Giờ hàm này đã được định nghĩa ở ngoài

                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.textContent = 'Rate';
                    submitButton.classList.remove('active');
                }
                if (removeRatingBtn) {
                    if (currentRating > 0) {
                        removeRatingBtn.style.display = 'inline-block';
                    } else {
                        removeRatingBtn.style.display = 'none';
                    }
                }

                // Hiển thị form
                form.style.display = 'flex'; // << Dòng này giờ sẽ chạy được

            } else {
                console.error(`ERROR: Rating form with selector "${formSelector}" NOT FOUND!`);
                alert(`Could not find rating details for movie ID ${movieId}.`);
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
        const stars = form.querySelectorAll('.star'); // Lấy tất cả sao trong form này
        const submitButton = form.querySelector('.btn-submit-rating');
        const removeRatingButton = form.querySelector('.btn-remove-rating');
        // Lưu trữ trạng thái selected ban đầu
        form.dataset.selectedValue = form.dataset.currentRating || "0";

        stars.forEach(star => {
            // --- Sự kiện HOVER vào sao ---
            star.addEventListener('mouseover', function() {
                const hoverValue = parseInt(this.dataset.value);
                updateStarsAndDisplay(form, hoverValue, true); // Gọi hàm global
            });
    
            // --- Sự kiện RỜI CHUỘT khỏi sao ---
            star.addEventListener('mouseout', function() {
                const selectedValue = parseInt(form.dataset.selectedValue || '0');
                updateStarsAndDisplay(form, selectedValue, false); // Gọi hàm global
            });
    
            // --- Sự kiện CLICK vào sao ---
            star.addEventListener('click', function() {
                const ratingValue = parseInt(this.dataset.value);
                form.dataset.selectedValue = ratingValue;
                console.log("Star clicked, selectedValue set to:", form.dataset.selectedValue);
                updateStarsAndDisplay(form, ratingValue, false); // Gọi hàm global
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.classList.add('active');
                    submitButton.textContent = 'Rate';
                }
                 // Đảm bảo không có lệnh gọi submitRating ở đây
            });
        });

        // --- Sự kiện CLICK vào nút Rate ---
        if (submitButton) {
            submitButton.addEventListener('click', function() {
                const movieId = form.dataset.movieId; // Lấy ID từ form
                const selectedValue = parseInt(form.dataset.selectedValue || '0');

                console.log(`Submit button clicked for movie ${movieId} with rating ${selectedValue}`); // Log

                if (selectedValue > 0) {
                     this.disabled = true;
                     this.textContent = 'Rating...';
                    if (typeof submitRating === 'function') {
                         submitRating(movieId, selectedValue); // Gọi AJAX
                         form.dataset.currentRating = selectedValue; // Cập nhật rating hiện tại
                         console.log("Calling submitRating function..."); // Log
                    } else {
                         console.error('submitRating function is not defined.');
                         this.disabled = false;
                         this.textContent = 'Rate';
                    }
                } else {
                     alert("Please select a rating first.");
                     console.log("No rating selected to submit."); // Log
                }
            });
        }

        // --- Sự kiện CLICK vào nút Remove Rating ---
        if (removeRatingButton) {
            removeRatingButton.addEventListener('click', function() {
                const movieId = form.dataset.movieId; // Lấy ID từ form
                console.log(`Remove button clicked for movie ${movieId}`); // Log
                if (confirm('Are you sure you want to remove your rating?')) {
                    if (typeof removeRating === 'function') {
                        removeRating(movieId); // Gọi AJAX
                        console.log("Calling removeRating function..."); // Log
                        // Cập nhật UI ngay
                        form.dataset.selectedValue = "0";
                        form.dataset.currentRating = "0";
                        updateStarsAndDisplay(form, 0, false); // Reset UI, truyền form vào
                        if (submitButton) submitButton.disabled = true;
                        this.style.display = 'none';
                    } else {
                        console.error('removeRating function is not defined.');
                    }
                }
            });
        }
    });
     
     
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