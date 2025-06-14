@extends('layout')
<style>
    main > .container {
        padding: 60px 15px 0;
    }
</style>
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="container-fluid"><a class="navbar-brand" href="#">IP Management System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation"><span
                class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item"><a class="nav-link" href="#">IP Address Audit Logs</a></li>
                <li class="nav-item"><a class="nav-link" href="#">User Login Audit Logs</a></li>
            </ul>
        </div>
    </div>
</nav>
<main class="flex-shrink-0">
    <div id="ip-management-system-dashboard"></div>
</main>
