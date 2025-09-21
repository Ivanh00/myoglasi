<!-- PazAriO Logo -->
<div class="flex items-center justify-center">
    <!-- Light theme logo -->
    <img src="{{ asset('images/logo-light.svg') }}"
         alt="PazAriO"
         class="h-16 w-auto dark:hidden"
         {{ $attributes }}>

    <!-- Dark theme logo -->
    <img src="{{ asset('images/logo-dark.svg') }}"
         alt="PazAriO"
         class="h-16 w-auto hidden dark:block"
         {{ $attributes }}>
</div>
