<div class="left-side-menu">
   <!-- LOGO -->
   <a href="index.html" class="logo text-center logo-light">
   <span class="logo-lg">
   <img src="dist/assets/images/logo.png" alt="" height="16" />
   </span>
   <span class="logo-sm">
   <img src="dist/assets/images/logo_sm.png" alt="" height="16" />
   </span>
   </a>
   <!-- LOGO -->
   <a href="index.html" class="logo text-center logo-dark">
   <span class="logo-lg">
   <img src="dist/assets/images/logo-dark.png" alt="" height="16" />
   </span>
   <span class="logo-sm">
   <img src="dist/assets/images/logo_sm_dark.png" alt="" height="16" />
   </span>
   </a>
   <div class="h-100" id="left-side-menu-container" data-simplebar>
      <!--- Sidemenu -->
      <ul class="metismenu side-nav">
         <li class="side-nav-title side-nav-item">Navigation</li>
         <li class="side-nav-item">
            <a href="{{path_for('home')}}" class="side-nav-link">
            <i class="uil-home-alt"></i>
            <span> Dashboards </span>
            </a>
         </li>
         <li class="side-nav-item">
            <a href="javascript: void(0);" class="side-nav-link">
            <i class="uil-store"></i>
            <span> Artikelen </span>
            <span class="menu-arrow"></span>
            </a>
            <ul class="side-nav-second-level" aria-expanded="false">
               {% if user.checkPermissionByRouteName('ProductsIndex') or auth.user.super%}
               <li>
                  <a href="{{path_for('ProductsIndex')}}">Alle Artikelen</a>
               </li>
               {% endif %}
               <li>
                  <a href="apps-ecommerce-products-details.html">Products Details</a>
               </li>
               <li>
                  <a href="apps-ecommerce-orders.html">Orders</a>
               </li>
               <li>
                  <a href="apps-ecommerce-orders-details.html">Order Details</a>
               </li>
               <li>
                  <a href="apps-ecommerce-customers.html">Customers</a>
               </li>
               <li>
                  <a href="apps-ecommerce-shopping-cart.html">Shopping Cart</a>
               </li>
               <li>
                  <a href="apps-ecommerce-checkout.html">Checkout</a>
               </li>
               <li>
                  <a href="apps-ecommerce-sellers.html">Sellers</a>
               </li>
            </ul>
         </li>
      </ul>
   </div>
   <!-- menu -->
   <!-- Sidebar -left -->
</div>