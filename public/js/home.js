// Đảm bảo code này chạy sau khi DOM đã sẵn sàng
document.addEventListener("DOMContentLoaded", function() {

    // --- Code khởi tạo Swiper News Slider ---
    // Kiểm tra xem phần tử Swiper có tồn tại trên trang không
    if (document.querySelector(".mySwiper")) {
        try {
            var swiper = new Swiper(".mySwiper", {
                // slidesPerView: 1, // Các cấu hình ví dụ
                // spaceBetween: 10,
                 loop: true, // Ví dụ thêm loop
                 autoplay: { // Ví dụ thêm autoplay
                    delay: 5000,
                    disableOnInteraction: false,
                 },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                pagination: { // Ví dụ thêm pagination nếu muốn
                    el: ".swiper-pagination",
                    clickable: true,
                },
                
                // breakpoints: { ... } // Cấu hình responsive nếu cần
            });
        } catch (e) {
            console.error("Swiper initialization failed:", e);
            // Có thể do thư viện Swiper chưa được load hoặc cấu hình sai
        }
    } else {
        console.log("Swiper container (.mySwiper) not found on this page.");
    }


    // --- Code khởi tạo Movie/TV/Celeb Sliders ---
    const productContainers = [...document.querySelectorAll('.movie-container')];
    // Quan trọng: Chỉ lấy nút tương ứng với từng container
    // Cách lấy nút prev/next cần chính xác hơn, ví dụ: dựa vào section cha
    // Đoạn code gốc lấy tất cả nút có thể bị lẫn lộn nếu có nhiều slider

    productContainers.forEach((container) => {
        // Tìm nút pre/next trong cùng section cha với container
        const section = container.closest('section.movie'); // Tìm section cha gần nhất
        if (!section) return; // Bỏ qua nếu không tìm thấy section cha

        const preBtn = section.querySelector('.pre-btn');
        const nxtBtn = section.querySelector('.nxt-btn');

        if (preBtn && nxtBtn) {
            let containerWidth = container.offsetWidth; // Lấy chiều rộng thực tế
            // Có thể điều chỉnh mức độ cuộn (ví dụ 80% chiều rộng)
            let scrollAmount = containerWidth * 0.8;

            nxtBtn.addEventListener('click', () => {
                container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });
            preBtn.addEventListener('click', () => {
                 container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });
        }
    });

}); // Kết thúc DOMContentLoaded