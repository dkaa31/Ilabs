<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    @stack('styles')
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        @include('guru.template.part.sidebar')
    </div>

    <!-- Overlay untuk mobile (muncul saat sidebar terbuka) -->
    <div class="sidebar-overlay"></div>

    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper d-flex flex-column">
        <!-- Navbar -->
        @include('guru.template.part.navbar')

        <div class="page-title-bar bg-light-blue py-3 px-4">
            <h4 class="mb-0">@yield('page-title')</h4>
        </div>

        <div class="flex-grow-1 p-3 p-md-4">
            @yield('content')
        </div>

        @include('guru.template.part.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const overlay = document.querySelector('.sidebar-overlay');

            if (!sidebar || !sidebarToggle || !overlay) return;

            // Fungsi tutup sidebar
            function closeSidebar() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                document.body.style.overflow = ''; // unlock scroll
            }

            // Buka/tutup via tombol
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
                document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
            });

            // Tutup saat klik di overlay
            overlay.addEventListener('click', closeSidebar);

            // Tutup saat klik di luar sidebar (opsional tambahan)
            document.addEventListener('click', function(e) {
                if (sidebar.classList.contains('show')) {
                    const isClickInsideSidebar = sidebar.contains(e.target);
                    const isClickOnToggle = sidebarToggle.contains(e.target);
                    if (!isClickInsideSidebar && !isClickOnToggle) {
                        closeSidebar();
                    }
                }
            });

            // Toggle submenu (tetap pertahankan)
            document.querySelectorAll('.menu-parent').forEach(parent => {
                parent.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-target');
                    const submenu = document.getElementById(targetId);
                    if (!submenu) return;
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';
                    if (isExpanded) {
                        submenu.style.display = 'none';
                        this.setAttribute('aria-expanded', 'false');
                    } else {
                        submenu.style.display = 'block';
                        this.setAttribute('aria-expanded', 'true');
                    }
                });
            });

            // Auto-open active submenu
            document.querySelectorAll('.menu-parent').forEach(parent => {
                const targetId = parent.getAttribute('data-target');
                const submenu = document.getElementById(targetId);
                if (submenu && submenu.querySelector('.active')) {
                    submenu.style.display = 'block';
                    parent.setAttribute('aria-expanded', 'true');
                }
            });
        });
    </script>
</body>

</html>
