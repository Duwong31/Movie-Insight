
// Thêm phim vào watchlist
function addToWatchlist(movieId, buttonElement) {
    if (!config.isLoggedIn) {
        window.location.href = `${config.urls.login}?redirect=${encodeURIComponent(window.location.href)}`;
        return;
    }

    // Vô hiệu hóa nút và hiển thị trạng thái loading (nếu có buttonElement)
    if (buttonElement) {
        buttonElement.disabled = true;
        buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
    }

    fetch(config.urls.addToWatchlist, { // Sử dụng URL từ config
        method: 'POST', // <<<< Đổi thành POST
        headers: {
            'Content-Type': 'application/json', // <<<< Thêm header
            'Accept': 'application/json',
            'X-CSRF-TOKEN': config.csrfToken // <<<< Thêm CSRF token
        },
        body: JSON.stringify({ movie_id: movieId }) // <<<< Gửi movie_id trong body
    })
    .then(response => {
         // Xử lý các lỗi HTTP trước khi parse JSON
        if (!response.ok) {
             // Nếu là lỗi 401 (Unauthorized) hoặc 419 (CSRF Token Mismatch), có thể chuyển hướng đăng nhập
             if (response.status === 401 || response.status === 419) {
                 window.location.href = config.urls.login;
             }
            // Ném lỗi để catch xử lý, kèm theo status code
             return response.json().then(errData => {
                 throw { status: response.status, message: errData.message || 'Request failed' };
             });
        }
        return response.json(); // Parse JSON nếu thành công
    })
    .then(data => {
        if (data.success) {
            // alert(data.message || "Added to watchlist!"); // Có thể dùng toast notification thay alert
            console.log(data.message || "Added to watchlist!");

            // Cập nhật nút (nếu có)
            if (buttonElement) {
                buttonElement.innerHTML = '<i class="fas fa-check"></i> Added'; // Đổi thành trạng thái "Added"
                buttonElement.classList.remove('addToWatchlist-btn'); 
                buttonElement.classList.add('remove-watchlist-btn'); 
                // buttonElement.dataset.movieId = movieId; 
                // buttonElement.onclick = () => removeFromWatchlist(movieId, buttonElement);
                buttonElement.disabled = false; // Kích hoạt lại nút với chức năng mới
            }
        } else {
             // Hiển thị lỗi cụ thể từ server nếu có
            alert(data.message || "Failed to add movie. Movie might already be in watchlist.");
            // Khôi phục nút về trạng thái ban đầu nếu có lỗi
            if (buttonElement) {
                 buttonElement.disabled = false;
                 buttonElement.innerHTML = '<i class="fa-solid fa-plus"></i> Add to Watchlist'; // Hoặc text gốc
            }
        }
    })
    .catch(error => {
        console.error('Error adding to watchlist:', error);
         // Hiển thị lỗi chung hoặc lỗi cụ thể nếu có message
         alert(`Error: ${error.message || 'Could not add to watchlist. Please try again.'}`);
         // Khôi phục nút nếu có lỗi
         if (buttonElement) {
             buttonElement.disabled = false;
             buttonElement.innerHTML = '<i class="fa-solid fa-plus"></i> Add to Watchlist';
         }
    });
}

function removeFromWatchlist(movieId, buttonElement) {
    if (!config.isLoggedIn) {
       alert("Please log in to manage your watchlist.");
       return;
   }

   // Vô hiệu hóa nút và hiển thị loading (nếu buttonElement được truyền vào)
    const targetButton = buttonElement || document.querySelector(`.remove-button[data-movie-id="${movieId}"], .remove-watchlist-btn[data-movie-id="${movieId}"]`);
    if (targetButton) {
       targetButton.disabled = true;
       targetButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    }

   fetch(config.urls.removeFromWatchlist, { // Sử dụng URL từ config
       method: 'DELETE', // <<<< Sử dụng DELETE như trong route
       headers: {
           'Content-Type': 'application/json', // <<<< Thêm header
           'Accept': 'application/json',
           'X-CSRF-TOKEN': config.csrfToken // <<<< Thêm CSRF token
       },
        body: JSON.stringify({ movie_id: movieId }) // <<<< Gửi movie_id trong body
   })
   .then(response => {
       if (!response.ok) {
           if (response.status === 401 || response.status === 419) {
               window.location.href = config.urls.login;
           }
            return response.json().then(errData => {
                throw { status: response.status, message: errData.message || 'Request failed' };
            });
       }
       return response.json();
   })
   .then(data => {
       if (data.success) {
           console.log(data.message || "Removed from watchlist!");

            // Nếu nút được truyền vào (thường là từ trang chi tiết phim)
            if (buttonElement) {
                buttonElement.innerHTML = '<i class="fa-solid fa-plus"></i> Add to Watchlist';
                buttonElement.classList.remove('remove-watchlist-btn');
                buttonElement.classList.add('addToWatchlist-btn');
                // buttonElement.onclick = () => addToWatchlist(movieId, buttonElement);
                buttonElement.disabled = false;
            }

            // Nếu đang ở trang watchlist, xóa phần tử khỏi DOM
            // Sử dụng ID đã định nghĩa trong watchlist/index.blade.php
            const watchlistItem = document.getElementById(`watchlist-item-${movieId}`);
            if (watchlistItem) {
                // Hiệu ứng xóa mờ dần (tùy chọn)
                watchlistItem.style.transition = 'opacity 0.5s ease-out';
                watchlistItem.style.opacity = '0';
                setTimeout(() => {
                    watchlistItem.remove();
                    // Kiểm tra xem watchlist có rỗng không sau khi xóa
                    if (document.querySelectorAll('.watchlist-item').length === 0) {
                        const container = document.querySelector('.watchlist-items');
                        if (container) {
                            container.innerHTML = `
                                <div class="empty-watchlist">
                                    <p>Your watchlist is empty.</p>
                                    <p>Start adding movies <a href="{{ route('movies.list') }}">here</a>!</p>
                                </div>`;
                        }
                    }
                }, 500); // Chờ hiệu ứng hoàn thành
            }
       } else {
           alert(data.message || "Failed to remove movie.");
            // Khôi phục nút nếu có lỗi
            if (targetButton) {
                targetButton.disabled = false;
                targetButton.innerHTML = buttonElement ? '<i class="fas fa-check"></i> Added' : '<i class="fa-solid fa-trash-can"></i>'; // Khôi phục icon phù hợp
            }
       }
   })
   .catch(error => {
       console.error('Error removing from watchlist:', error);
       alert(`Error: ${error.message || 'Could not remove from watchlist. Please try again.'}`);
        // Khôi phục nút nếu có lỗi
        if (targetButton) {
            targetButton.disabled = false;
             targetButton.innerHTML = buttonElement ? '<i class="fas fa-check"></i> Added' : '<i class="fa-solid fa-trash-can"></i>';
        }
   });
}

// Gửi đánh giá (Ví dụ - Cần cập nhật dựa trên code gốc của bạn)
function submitRating(movieId, ratingValue) {
    if (!config.isLoggedIn) {
        alert("Please log in to rate this movie.");
        window.location.href = `${config.urls.login}?redirect=${encodeURIComponent(window.location.href)}`;
        return;
    }
    // Không cần gửi loggedInUserId, backend sẽ tự lấy từ Auth::id()

    fetch(config.urls.rateMovie, { // Sử dụng URL từ config
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': config.csrfToken // Thêm CSRF token
        },
        // Gửi movie_id và rating. Backend sẽ tự lấy user_id
        body: JSON.stringify({ movie_id: movieId, rating: ratingValue })
    })
    .then(response => {
        if (!response.ok) {
             if (response.status === 401 || response.status === 419) {
                window.location.href = config.urls.login;
            }
            // Cố gắng parse lỗi JSON từ server
            return response.json().then(errData => {
                 throw { status: response.status, message: errData.message || 'Rating submission failed' };
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // alert("Thank you for rating!"); // Thông báo thành công (có thể dùng toast)
            console.log("Rating submitted successfully.");
            // Gọi hàm cập nhật UI (nằm trong script.js hoặc ajax.js)
             if (typeof updateRatingUI === "function") {
                 // Backend cần trả về new_average_rating
                updateRatingUI(movieId, ratingValue, data.new_average_rating);
             } else {
                 console.warn("updateRatingUI function is not defined. UI might not reflect the change.");
                 // Fallback: reload trang để thấy thay đổi (không lý tưởng)
                 // window.location.reload();
             }
        } else {
            // Hiển thị lỗi cụ thể từ server
            alert(data.message || "There was an error submitting your rating.");
        }
    })
    .catch(error => {
        console.error('Error submitting rating:', error);
         alert(`Error: ${error.message || 'Could not submit rating. Please try again.'}`);
    });
}

// Xoá đánh giá (Ví dụ)
function removeRating(movieId) {
    if (!config.isLoggedIn) {
       alert("Please log in to remove your rating.");
        window.location.href = `${config.urls.login}?redirect=${encodeURIComponent(window.location.href)}`;
       return;
   }
    // Không cần gửi loggedInUserId

    fetch(config.urls.removeRating, { // Sử dụng URL từ config
       method: 'POST', // Route của bạn đang là POST
       headers: {
           'Content-Type': 'application/json',
           'Accept': 'application/json',
           'X-CSRF-TOKEN': config.csrfToken // Thêm CSRF
       },
       // Chỉ cần gửi movie_id, backend sẽ tìm rating của user đang đăng nhập cho movie này
       body: JSON.stringify({ movie_id: movieId })
       // Nếu route là DELETE, đổi method và không cần Content-Type nếu body rỗng
       // method: 'DELETE',
       // headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': config.csrfToken },
       // body: JSON.stringify({ movie_id: movieId }) // Hoặc không cần body nếu movie_id trong URL
   })
    .then(response => {
       if (!response.ok) {
            if (response.status === 401 || response.status === 419) {
               window.location.href = config.urls.login;
           }
            return response.json().then(errData => {
                throw { status: response.status, message: errData.message || 'Rating removal failed' };
            });
       }
       return response.json();
   })
   .then(data => {
       if (data.success) {
           // alert("Your rating has been removed."); // Thông báo thành công
           console.log("Rating removed successfully.");
            if (typeof updateRatingUI === "function") {
                // Truyền null cho ratingValue khi xóa
                updateRatingUI(movieId, null, data.new_average_rating);
            } else {
                 console.warn("updateRatingUI function is not defined. UI might not reflect the change.");
                // window.location.reload();
            }
       } else {
           alert(data.message || "There was an error removing your rating.");
       }
   })
   .catch(error => {
       console.error('Error removing rating:', error);
        alert(`Error: ${error.message || 'Could not remove rating. Please try again.'}`);
   });
}


// function showMovieDetails(movieId) {
//     fetch('http://localhost/MovieInsight_Project/modules/view/movie_detail.php', {
//         method: 'POST',
//         headers: { 'Content-Type': 'application/json' },
//         body: JSON.stringify({ movie_id: movieId })
//     })
//     .then(response => response.text())
//     .then(data => {
//         document.getElementById('movie-details').innerHTML = data; // Hiển thị dữ liệu trong phần tử có id là movie-details
//     })
//     .catch(error => console.error('Error:', error));
// }

// Hàm cập nhật giao diện sau khi gửi hoặc xoá đánh giá
function updateRatingUI(movieId, ratingValue, newAverageRating) {
    console.log(`Running updateRatingUI for ${movieId}. New Rating: ${ratingValue}, New Avg: ${newAverageRating}`);

    const rateLink = document.querySelector(`.rate-link[data-movie-id="${movieId}"]`);
    const averageRatingDisplay = document.querySelector(`.average-rating[data-movie-id="${movieId}"]`);
    const form = document.querySelector(`.rating-form[data-movie-id="${movieId}"]`);

    if (rateLink) {
         if (ratingValue) {
             rateLink.innerHTML = `★ ${Math.round(ratingValue)}`; // Sử dụng Math.round
             rateLink.classList.add('rated');
         } else {
             rateLink.innerHTML = `☆ Rate`;
             rateLink.classList.remove('rated');
         }
         console.log("Updated rateLink content");
    } else {
         console.warn("Could not find .rate-link for movie", movieId);
    }

    // Cần một element trên card để hiển thị average rating, ví dụ class="average-rating"
    if (averageRatingDisplay && typeof newAverageRating !== 'undefined' && newAverageRating !== null) {
        averageRatingDisplay.textContent = `★ ${parseFloat(newAverageRating).toFixed(1)}/10`;
        console.log("Updated average rating display");
    } else if (!averageRatingDisplay) {
         console.warn("Could not find .average-rating display element for movie", movieId);
    }


    if (form) {
        const submitButton = form.querySelector('.btn-submit-rating');
        if(submitButton){
            submitButton.disabled = true;
            submitButton.textContent = 'Rate';
        }
        form.dataset.currentRating = ratingValue || 0; // Cập nhật rating hiện tại trên form
        form.style.display = 'none'; // Đóng form
        console.log("Closed rating form for movie", movieId);
    } else {
         console.warn("Could not find rating form to close for movie", movieId);
    }
}