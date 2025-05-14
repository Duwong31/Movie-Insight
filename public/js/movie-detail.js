// public/js/movie-review.js
document.addEventListener('DOMContentLoaded', function () {
    const reviewSidebar = document.getElementById('reviewSidebar');

    // Only proceed if the review sidebar element exists (meaning user is likely authenticated and elements are on page)
    if (!reviewSidebar) {
        // console.log('Review sidebar not found. Exiting movie-review.js setup.');
        return;
    }

    const addReviewBtn = document.getElementById('addReviewBtn');
    const closeReviewSidebarBtn = document.getElementById('closeReviewSidebar');
    const cancelReviewSidebarBtn = document.getElementById('cancelReviewSidebar');
    const reviewSidebarOverlay = document.getElementById('reviewSidebarOverlay');
    const reviewForm = document.getElementById('reviewForm');
    const reviewRatingInput = document.getElementById('reviewRatingInput');
    const stars = reviewSidebar.querySelectorAll('.stars-input .sidebar-star');
    const ratingDisplay = reviewSidebar.querySelector('.rating-display');
    const reviewContent = document.getElementById('reviewContent');
    const charCountDisplay = document.getElementById('charCount');
    const reviewFormErrors = document.getElementById('reviewFormErrors');
    const reviewsList = document.getElementById('reviewsList');
    const noReviewsMessage = document.getElementById('noReviewsMessage');

    // Get movieId and initialUserRating from data attributes on the form
    const movieId = reviewForm.dataset.movieId;
    const initialUserRating = reviewForm.dataset.initialUserRating ? parseInt(reviewForm.dataset.initialUserRating) : null;

    function openSidebar() {
        reviewSidebar.classList.add('open');
        reviewSidebarOverlay.classList.add('open');
        // Pre-fill rating from main page if it exists
        updateStars(initialUserRating || (reviewRatingInput.value ? parseInt(reviewRatingInput.value) : 0));
    }

    function closeSidebar() {
        reviewSidebar.classList.remove('open');
        reviewSidebarOverlay.classList.remove('open');
        if (reviewForm) reviewForm.reset(); // Reset form on close
        updateStars(0); // Reset stars
        if (charCountDisplay) charCountDisplay.textContent = '0';
        if (reviewFormErrors) {
            reviewFormErrors.style.display = 'none';
            reviewFormErrors.innerHTML = '';
        }
    }

    if (addReviewBtn) {
        addReviewBtn.addEventListener('click', openSidebar);
    }
    if (closeReviewSidebarBtn) {
        closeReviewSidebarBtn.addEventListener('click', closeSidebar);
    }
    if (cancelReviewSidebarBtn) {
        cancelReviewSidebarBtn.addEventListener('click', closeSidebar);
    }
    if (reviewSidebarOverlay) {
        reviewSidebarOverlay.addEventListener('click', closeSidebar);
    }

    // Star rating interaction
    function updateStars(currentRating) {
        stars.forEach(star => {
            if (parseInt(star.dataset.value) <= currentRating) {
                star.classList.add('selected');
                star.textContent = '★'; // Filled star
            } else {
                star.classList.remove('selected');
                star.textContent = '☆'; // Empty star
            }
        });
        if (reviewRatingInput) reviewRatingInput.value = currentRating > 0 ? currentRating : '';
        if (ratingDisplay) ratingDisplay.textContent = currentRating > 0 ? `${currentRating}/10` : '?/10';
    }

    if (stars.length > 0) {
        stars.forEach(star => {
            star.addEventListener('mouseover', () => {
                const hoverValue = parseInt(star.dataset.value);
                stars.forEach(s => {
                    s.classList.toggle('hovered', parseInt(s.dataset.value) <= hoverValue);
                    s.textContent = parseInt(s.dataset.value) <= hoverValue ? '★' : '☆';
                });
            });
            star.addEventListener('mouseout', () => {
                stars.forEach(s => s.classList.remove('hovered'));
                updateStars(reviewRatingInput.value ? parseInt(reviewRatingInput.value) : 0); // Revert to selected
            });
            star.addEventListener('click', () => {
                const value = parseInt(star.dataset.value);
                updateStars(value);
                reviewRatingInput.value = value;
                // Gửi AJAX cập nhật điểm rating
                fetch('/rate/movie', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        movie_id: movieId,
                        rating: value
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Có thể cập nhật điểm trung bình ngoài giao diện nếu muốn
                        // alert('Đã cập nhật điểm đánh giá!');
                    }
                });
            });
        });
        // Initialize stars
        updateStars(initialUserRating || (reviewRatingInput && reviewRatingInput.value ? parseInt(reviewRatingInput.value) : 0));
    }


    // Character count for review content
    if (reviewContent && charCountDisplay) {
        reviewContent.addEventListener('input', () => {
            const count = reviewContent.value.length;
            charCountDisplay.textContent = count;
            if (count >= 400) {
                charCountDisplay.style.color = 'green';
            } else {
                charCountDisplay.style.color = 'inherit';
            }
        });
    }

    // AJAX Form Submission
    if (reviewForm) {
        reviewForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            // movieId is already defined from form's data-attribute
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.textContent = 'Submitting...';

            if (reviewFormErrors) {
                reviewFormErrors.style.display = 'none';
                reviewFormErrors.innerHTML = '';
            }

            fetch(`/movies/${movieId}/reviews`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.success);
                    closeSidebar();
                    const newReviewHtml = `
                        <div class="review-item">
                            <div class="review-item-header">
                                <strong>${data.review.user.name}</strong>
                                ${data.review.rating_given ? `
                                <span class="review-stars">
                                    ${Array.from({length: 10}, (_, i) => `<span class="star ${i < data.review.rating_given ? 'selected' : ''}">★</span>`).join('')}
                                    (${data.review.rating_given}/10)
                                </span>` : ''}
                                <small class="review-date">Just now</small>
                            </div>
                            <h4>${escapeHtml(data.review.title)}</h4>
                            ${data.review.has_spoiler ? '<p class="spoiler-warning"><strong>Warning: This review may contain spoilers!</strong></p>' : ''}
                            <p class="review-content">${escapeHtml(data.review.content.substring(0,300))}
                                ${data.review.content.length > 300 ? `<a href="#" class="read-more-review" data-fulltext="${nl2br(escapeHtml(data.review.content))}">Read more</a>` : ''}
                            </p>
                            <div class="full-review-content" style="display:none;"></div>
                        </div>`;
                    if (reviewsList) reviewsList.insertAdjacentHTML('afterbegin', newReviewHtml);
                    if (noReviewsMessage) noReviewsMessage.style.display = 'none';

                    if (formData.get('rating')) {
                        const mainRateButton = document.querySelector(`.rate-link[data-movie-id="${movieId}"]`);
                        if (mainRateButton) {
                            mainRateButton.innerHTML = `★ ${formData.get('rating')}`;
                        }
                        const mainRatingFormStars = document.querySelectorAll(`.rating-form[data-movie-id="${movieId}"] .star`);
                        mainRatingFormStars.forEach(starEl => {
                            starEl.classList.toggle('selected', parseInt(starEl.dataset.value) <= parseInt(formData.get('rating')));
                        });
                        const removeRatingBtn = document.querySelector(`.remove-rating-btn[data-movie-id="${movieId}"]`);
                        if(removeRatingBtn) removeRatingBtn.style.display = 'block';
                    }

                } else if (data.errors) {
                    let errorHtml = '<ul>';
                    for (const field in data.errors) {
                        data.errors[field].forEach(error => {
                            errorHtml += `<li>${escapeHtml(error)}</li>`;
                        });
                    }
                    errorHtml += '</ul>';
                    if (reviewFormErrors) {
                        reviewFormErrors.innerHTML = errorHtml;
                        reviewFormErrors.style.display = 'block';
                    }
                } else {
                    if (reviewFormErrors) {
                        reviewFormErrors.innerHTML = 'An unexpected error occurred.';
                        reviewFormErrors.style.display = 'block';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (reviewFormErrors) {
                    reviewFormErrors.innerHTML = 'Request failed. Please try again.';
                    reviewFormErrors.style.display = 'block';
                }
            })
            .finally(() => {
                 submitButton.disabled = false;
                 submitButton.textContent = originalButtonText;
            });
        });
    }

    // "Read more" for long reviews
    if (reviewsList) {
        reviewsList.addEventListener('click', function(event) {
            if (event.target.classList.contains('read-more-review')) {
                event.preventDefault();
                const link = event.target;
                const reviewItem = link.closest('.review-item');
                const shortContentP = reviewItem.querySelector('.review-content');
                const fullContentDiv = reviewItem.querySelector('.full-review-content');

                if (link.textContent === "Read more") {
                    fullContentDiv.innerHTML = link.dataset.fulltext;
                    shortContentP.style.display = 'none';
                    fullContentDiv.style.display = 'block';
                    link.textContent = "Read less";
                } else {
                    shortContentP.style.display = 'block';
                    fullContentDiv.style.display = 'none';
                    link.textContent = "Read more";
                }
            }
        });
    }

    function escapeHtml(unsafe) {
        if (unsafe === null || typeof unsafe === 'undefined') return '';
        return unsafe
             .toString()
             .replace(/&/g, "&")
             .replace(/</g, "<")
             .replace(/>/g, ">")
             .replace(/"/g, "&quot;")
             .replace(/'/g, "'");
    }
    function nl2br(str) {
        if (typeof str === 'undefined' || str === null) {
            return '';
        }
        return str.replace(/\r\n|\r|\n/g, '<br>');
    }

    // Review slider logic
    (function() {
        const slider = document.querySelector('.reviews-slider-inner');
        const items = document.querySelectorAll('.reviews-slider-inner .review-item');
        const prevBtn = document.querySelector('.prev-review-btn');
        const nextBtn = document.querySelector('.next-review-btn');
        const visibleCount = 4;
        let currentIndex = 0;

        function updateSlider() {
            const width = items[0]?.offsetWidth || 0;
            slider.style.transform = `translateX(-${currentIndex * width}px)`;
            if (prevBtn) prevBtn.disabled = currentIndex === 0;
            if (nextBtn) nextBtn.disabled = currentIndex + visibleCount >= items.length;
        }

        if (prevBtn && nextBtn && items.length > visibleCount) {
            prevBtn.addEventListener('click', function () {
                if (currentIndex > 0) {
                    currentIndex -= visibleCount;
                    if (currentIndex < 0) currentIndex = 0;
                    updateSlider();
                }
            });
            nextBtn.addEventListener('click', function () {
                if (currentIndex + visibleCount < items.length) {
                    currentIndex += visibleCount;
                    updateSlider();
                }
            });
            updateSlider();
        } else if (prevBtn && nextBtn) {
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
        }
    })();
});