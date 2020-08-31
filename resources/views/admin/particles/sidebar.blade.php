 <!-- ========== Left Sidebar Start ========== -->
 <div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="{{ adminUrl() }}" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ adminUrl('admins') }}" class="waves-effect">
                        <i class="dripicons-user-group"></i>
                        <span>Admins</span>
                    </a>
                </li>

                <li>
                    <a href="{{ adminUrl('users') }}" class="waves-effect">
                        <i class="bx bxs-user-detail"></i>
                        <span>Users</span>
                    </a>
                </li>

  
                
                <li class="menu-title">Utilities</li>

                <li>
                    <a href="{{ adminUrl('category') }}" class="waves-effect">
                        <i class="dripicons-folder-open"></i>
                        <span>Categories</span>
                    </a>
                </li>

                <li>
                    <a href="{{ adminUrl('tag') }}" class="waves-effect">
                        <i class="bx bx-hash"></i>
                        <span>Tags</span>
                    </a>
                </li>

                <li>
                    <a href="{{ adminUrl('pictures') }}" class="waves-effect">
                        <i class="bx bx-image"></i>
                        <span>Pictures</span>
                    </a>
                </li>

                <li>
                    <a href="{{ adminUrl('collection') }}" class="waves-effect">
                        <i class="bx bx-heart"></i>
                        <span>Collection</span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->