{{-- resources/views/partials/footer.blade.php --}}

<footer id="footer">
    <div class="footer-container">
        <div class="footer-content">
            <h3>Movie Insight</h3>
            <p>A website offering the latest, high-quality reviews of the hottest movies with fast updates.</p>
        </div>

        <div class="footer-links">
            <h4>Links</h4>
            <ul>
                {{-- Sử dụng route() hoặc url() cho các link --}}
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('movies.list') }}">Movies</a></li>
                <li><a href="{{ route('tvshows.list') }}">TV Shows</a></li>
                <li><a href="#">About us</a></li> {{-- Cập nhật route nếu có trang about --}}
                <li><a href="#">Contact</a></li> {{-- Cập nhật route nếu có trang contact --}}
            </ul>
        </div>
        <div class="social-links">
            <h4>Follow us</h4>
            <div class="social-icons">
                <a href="https://facebook.com" target="_blank" class="facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="https://instagram.com" target="_blank" class="instagram"><i class="fab fa-instagram"></i></a>
                <a href="https://twitter.com" target="_blank" class="twitter"><i class="fab fa-twitter"></i></a>
                <a href="https://youtube.com" target="_blank" class="youtube"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    <hr>

    <div class="copyright">© {{ date('Y') }} Movie Insight. All rights reserved.</div> {{-- Lấy năm hiện tại động --}}
</footer>