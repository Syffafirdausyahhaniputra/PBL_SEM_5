<header class="header">
    <div class="brand-title">
        <span class="emoji">🐱</span> Akaneko <span class="emoji">🍜</span> Ramen
    </div>
    <nav class="navbar">
        <a href="#" data-text="Home" class="active">Home</a>
        <a href="#kategori" data-text="Kategori">Menu</a>
        <a href="#about" data-text="About me">About Us</a>  
        <a href="{{ url('login') }}" class="btn-login">Sign In</a>
    </nav>
</header>

<script>
    let lastScrollTop = 0;
    const header = document.querySelector('.header');

    window.addEventListener('scroll', function () {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop) {
            // Scroll ke bawah - sembunyikan header
            header.classList.add('hidden-header');
        } else {
            // Scroll ke atas - tampilkan header
            header.classList.remove('hidden-header');
        }

        lastScrollTop = scrollTop;
    });
</script>
