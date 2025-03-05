<nav class="sidebar background-color-primary position-fixed h-100" onmouseleave="closeSubmenus()">
    <div class="d-flex flex-column h-100">

        <div class="nav flex-column" id="sidebarLogo">
            <!-- Logo -->
            <a href="{{route('dashboard')}}" class="nav-link">
                <x-heroicon-o-squares-2x2 class="text-white" style="width: 20px" />
                <span>{{__('WhistleblowingTool')}}</span>
            </a>
        </div>

        @include('layouts.partials-sidebar.company')
        
        @include('layouts.partials-sidebar.affiliate')
        
        @include('layouts.partials-sidebar.owner')

        @include('layouts.partials-sidebar.investigator')

    </div>
</nav>

<script>
    function closeSubmenus() {
        const submenus = document.querySelectorAll('.submenu');
        submenus.forEach(submenu => {
            submenu.classList.remove('show');
        });
    }

    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function() {
            const submenu = this.nextElementSibling;
            if (submenu && submenu.classList.contains('submenu')) {
                closeSubmenus();
                submenu.classList.toggle('show');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const submenus = document.querySelectorAll('.submenu');
            submenus.forEach(submenu => {
                submenu.classList.add('show');
            });
        });
    });
</script>