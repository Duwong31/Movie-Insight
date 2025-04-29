// C:\Tailieu\Nam3\DATT_CNPM\MovieInsight\public\js\ajax.js

// Lưu ý: Các biến isLoggedIn, loggedInUserId, addToWatchlistUrl, removeFromWatchlistUrl,
// rateMovieUrl, removeRatingUrl, csrfToken được lấy từ biến toàn cục
// đã định nghĩa trong file Blade.

// Thêm phim vào watchlist
function addToWatchlist(movieId) {
    // Sử dụng biến toàn cục isLoggedIn
    if (!isLoggedIn) {
        alert("Please log in to add movies to your watchlist.");
        return;
    }
    // Sử dụng biến toàn cục addToWatchlistUrl
    fetch(`${addToWatchlistUrl}?movie_id=${movieId}`, { // Giả sử API dùng GET và movie_id trong query param
        method: 'GET', // Hoặc POST nếu API yêu cầu
        headers: {
            'Accept': 'application/json',
             // Thêm CSRF nếu dùng POST và API yêu cầu
            // 'X-CSRF-TOKEN': csrfToken
        },
        // body: JSON.stringify({ movie_id: movieId }) // Nếu dùng POST
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || "Added to watchlist!");
            // Cập nhật nút nếu cần (ví dụ đổi thành 'In Watchlist')
            const button = document.querySelector(`.addToWatchlist-btn[data-movie-id="${movieId}"]`);
            if (button) {
                button.textContent = 'In Watchlist';
                // Có thể thêm class để đổi style
            }
        } else {
            alert(data.message || "Failed to add movie.");
        }
    })
    .catch(error => console.error('Error adding to watchlist:', error));
}

function removeFromWatchlist(movieId) {
     if (!isLoggedIn) { // Luôn kiểm tra đăng nhập
        alert("Please log in to manage your watchlist.");
        return;
    }
     // Sử dụng biến toàn cục removeFromWatchlistUrl
    fetch(`${removeFromWatchlistUrl}?movie_id=${movieId}`, { // Giả sử API dùng GET/DELETE
        method: 'DELETE', // Hoặc GET/POST tùy API của bạn
        headers: {
            'Accept': 'application/json',
             // Thêm CSRF nếu API yêu cầu (thường cần cho DELETE/POST)
             'X-CSRF-TOKEN': csrfToken
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Cập nhật nút
            const button = document.querySelector(`.addToWatchlist-btn[data-movie-id="${movieId}"]`);
            if (button) {
                button.textContent = 'Add to watchlist';
            }
             // Nếu đang ở trang watchlist, xóa phần tử khỏi DOM
            const watchlistItem = document.getElementById(`movie-${movieId}`); // Giả sử item có ID này
             if (watchlistItem) {
                 watchlistItem.remove();
             } else {
                 alert(data.message || "Removed from watchlist!"); // Thông báo nếu không ở trang watchlist
             }
        } else {
            alert(data.message || "Failed to remove movie.");
        }
    })
    .catch(error => console.error('Error removing from watchlist:', error));
}

// Gửi đánh giá (Ví dụ - Cần cập nhật dựa trên code gốc của bạn)
function submitRating(movieId, ratingValue) {
    if (!isLoggedIn) {
        alert("Please log in to rate this movie.");
        return;
    }
    if (!loggedInUserId) { // Cần cả user ID
        console.error("Logged in User ID not found.");
        return;
    }

    fetch(rateMovieUrl, { // Sử dụng biến URL toàn cục
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken // Thêm CSRF token
        },
        // Sử dụng biến loggedInUserId toàn cục
        body: JSON.stringify({ movie_id: movieId, rating: ratingValue, user_id: loggedInUserId })
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert("Thank you for rating!");
            // Gọi hàm cập nhật UI (hàm này có thể nằm trong script.js hoặc ajax.js)
             if (typeof updateRatingUI === "function") { // Kiểm tra hàm tồn tại
                updateRatingUI(movieId, ratingValue, data.new_average_rating);
             }
            // Đóng form (logic đóng form có thể ở script.js hoặc gọi trực tiếp)
             const form = document.querySelector(`.rating-form[data-movie-id="${movieId}"]`);
             if (form) form.style.display = 'none';
        } else {
            alert(data.error || "There was an error submitting your rating.");
        }
    })
    .catch(error => {
        console.error('Error submitting rating:', error);
        alert("There was an error submitting your rating.");
    });
}

// Xoá đánh giá (Ví dụ)
function removeRating(movieId) {
     if (!isLoggedIn || !loggedInUserId) {
        alert("Please log in to remove your rating.");
        return;
    }
     fetch(removeRatingUrl, { // Sử dụng biến URL toàn cục
        method: 'POST', // Hoặc DELETE tùy API
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken // Thêm CSRF
        },
        body: JSON.stringify({ movie_id: movieId, user_id: loggedInUserId }) // Gửi user ID
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert("Your rating has been removed.");
             if (typeof updateRatingUI === "function") {
                 updateRatingUI(movieId, null, data.new_average_rating); // ratingValue là null khi xóa
             }
             const form = document.querySelector(`.rating-form[data-movie-id="${movieId}"]`);
             if (form) form.style.display = 'none';
        } else {
            alert(data.error || "There was an error removing your rating.");
        }
    })
    .catch(error => {
        console.error('Error removing rating:', error);
        alert("There was an error removing your rating.");
    });
}


// Hàm showMovieDetails cần xem lại URL fetch nếu dùng Laravel
// function showMovieDetails(movieId) { ... }


function showMovieDetails(movieId) {
    fetch('http://localhost/MovieInsight_Project/modules/view/movie_detail.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ movie_id: movieId })
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('movie-details').innerHTML = data; // Hiển thị dữ liệu trong phần tử có id là movie-details
    })
    .catch(error => console.error('Error:', error));
}

