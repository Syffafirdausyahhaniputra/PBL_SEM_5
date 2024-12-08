<header class="header">
    <div class="brand-title">
        <h3>JTI Certify</h3>
    </div>
    <nav class="navbar">
        <a href="#" data-text="Home" class="active">Home</a>
        <a href="#kategori" data-text="Kategori">Dosen</a>
        <a href="#about" data-text="About me">About Us</a>
        <a href="{{ url('login') }}" class="btn-login">Log In</a>
    </nav>
    <div class="hamburger-menu" id="hamburgerMenu">
        <span></span>
        <span></span>
        <span></span>
    </div>
</header>

<script>
    let lastScrollTop = 0;
    const header = document.querySelector('.header');
    const hamburgerMenu = document.getElementById('hamburgerMenu');
    const navbar = document.querySelector('.navbar');

    // Scroll behavior
    window.addEventListener('scroll', function () {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop) {
            header.classList.add('hidden-header');
        } else {
            header.classList.remove('hidden-header');
        }
        lastScrollTop = scrollTop;
    });

    // Hamburger menu toggle
    hamburgerMenu.addEventListener('click', function () {
        navbar.classList.toggle('navbar-visible');
    });
</script>
