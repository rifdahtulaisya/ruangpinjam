<!DOCTYPE html>
<html lang="id">
<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Peminjam Dashboard</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('assets-admin/img/2.svg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- DataTables CSS -->
<link href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css" rel="stylesheet">

<!-- DataTables Responsive -->
<link href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.dataTables.min.css" rel="stylesheet">

</head>

<body class="min-h-screen relative overflow-x-hidden fixed inset-0 -z-20
    bg-gradient-to-br
    from-blue-200
    via-blue-300
    to-indigo-400">
<!-- Sidebar -->
<x-sidebar-peminjam />

<!-- Navbar -->
<x-navbar-peminjam />
<div id="overlay"
         class="fixed inset-0 bg-black/30 z-30 hidden md:hidden">
    </div>


 {{-- KONTEN UTAMA --}}
      <main class="pt-8 pb-6 px-4 md:px-6 relative top-[90px] mb-24 md:ml-[320px] md:mr-3 md:rounded-3xl transition-all duration-300 flex flex-col max-w-full">
    @yield('content')
</main>

    <footer class="mt-10 px-6 py-4 text-sm text-slate-500 flex justify-between items-center
               md:ml-[320px] md:mr-3 transition-all duration-300">


    <div>
        © {{ date('Y') }} Sarprasku. All rights reserved.
    </div>

    <div class="flex gap-4">
        <span>Version 1.0</span>
        <span>•</span>
        <span>Admin Panel</span>
    </div>

</footer>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const ctx = document.getElementById('chartPeminjaman');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
        datasets: [{
            label: 'Jumlah Peminjaman',
            data: [12, 19, 10, 15, 22, 18, 25],
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,0.1)',
            tension: 0.4,
            fill: true,
            pointRadius: 4,
            pointBackgroundColor: '#3b82f6'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>



<script>
function confirmLogout() {

    Swal.fire({
        title: 'Logout?',
        text: "Anda akan keluar dari sistem",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#6366f1',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {

        if (result.isConfirmed) {

            document.getElementById('logout-form').submit();

        }

    });

}
</script>

<!-- Optional: JavaScript for Logout Confirmation -->
<script>
function confirmLogout() {
    if (!confirm('Apakah Anda yakin ingin keluar?')) {
        event.preventDefault();
    }
}
</script>

<script>

const burger = document.getElementById('burgerBtn');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');

burger.addEventListener('click', () => {

    sidebar.classList.toggle('-translate-x-full');

    overlay.classList.toggle('hidden');

});

overlay.addEventListener('click', () => {

    sidebar.classList.add('-translate-x-full');

    overlay.classList.add('hidden');

});

</script>




@stack('scripts')
</body>
</html>