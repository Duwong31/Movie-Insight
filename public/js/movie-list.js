document.addEventListener('DOMContentLoaded', function () {
    // Logic cho dropdown chính (In Theaters, At Home, ...)
    const mainFilterBtn = document.getElementById('mainFilterBtn');
    const mainFilterDropdownContent = document.getElementById('mainFilterDropdownContent');

    if (mainFilterBtn && mainFilterDropdownContent) {
        mainFilterBtn.addEventListener('click', function (event) {
            event.stopPropagation(); // Ngăn việc click bị lan ra document listener ngay
            mainFilterDropdownContent.classList.toggle('show');
        });

        // Đóng dropdown nếu click ra ngoài
        document.addEventListener('click', function (event) {
            if (!mainFilterBtn.contains(event.target) && !mainFilterDropdownContent.contains(event.target)) {
                mainFilterDropdownContent.classList.remove('show');
            }
        });
    }

    // Gắn sự kiện cho các nút watchlist (đã có trong ajax.js hoặc script.js của bạn)
    // Đảm bảo các hàm addToWatchlist và removeFromWatchlist được định nghĩa và hoạt động
    // Đoạn code dưới đây giả định bạn có một hàm chung để xử lý việc này
    function handleWatchlistAction(button, movieId, action) {
        // Vô hiệu hóa nút tạm thời
        button.disabled = true;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        const url = (action === 'add') ? config.urls.addToWatchlist : config.urls.removeFromWatchlist;
        const method = (action === 'add') ? 'POST' : 'DELETE';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': config.csrfToken
            },
            body: JSON.stringify({ movie_id: movieId })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (action === 'add') {
                    button.innerHTML = '<i class="fas fa-check"></i> Watchlist';
                    button.classList.remove('add-watchlist-btn');
                    button.classList.add('in-watchlist', 'removeWatchlist-btn');
                } else {
                    button.innerHTML = '<i class="fas fa-plus"></i> Watchlist';
                    button.classList.remove('in-watchlist', 'removeWatchlist-btn');
                    button.classList.add('add-watchlist-btn');
                }
            } else {
                alert(data.message || 'Action failed. Please try again.');
                button.innerHTML = originalText; // Khôi phục text nếu lỗi
            }
        })
        .catch(error => {
            console.error('Watchlist error:', error);
            alert(error.message || 'An error occurred. Please try again.');
            button.innerHTML = originalText; // Khôi phục text nếu lỗi
        })
        .finally(() => {
            button.disabled = false; // Kích hoạt lại nút
        });
    }

    document.querySelectorAll('.add-watchlist-btn').forEach(button => {
        button.addEventListener('click', function () {
            const movieId = this.dataset.movieId;
            handleWatchlistAction(this, movieId, 'add');
        });
    });

    document.querySelectorAll('.removeWatchlist-btn').forEach(button => {
        button.addEventListener('click', function () {
            const movieId = this.dataset.movieId;
            handleWatchlistAction(this, movieId, 'remove');
        });
    });

});