<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>

<body>
    <div>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Tes Junior Programmer </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>


    @if($produk->isNotEmpty())
    <section>
        <div class="container">
            <div class="mt-4">
                <div class="d-flex align-items-center">
                    <div class="p-2 flex-grow-1">
                        <a href="{{url('tambah/produk')}}" class="me-2">
                            <button class="btn btn-outline-success">Tambah</button>
                        </a>
                    </div>
                    <div class="p-2">
                        <div class="d-flex align-items-center">
                            <div class="p-2">
                                <h6>Status</h6>
                            </div>
                            <div class="p-2">
                                <select onchange="window.location.href = this.value;" class="form-select" name="kategori" aria-label="Default select example">
                                    <option value="{{ url('') }}" {{ $status == '' ? 'selected' : '' }}>Semua</option>
                                    <option value="{{ url('1') }}" {{ $status == 1 ? 'selected' : '' }}>bisa dijual</option>
                                    <option value="{{ url('2') }}" {{ $status == 2 ? 'selected' : '' }}>tidak bisa dijual</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            @if(session('success'))
            <div id="success-alert" class="alert alert-success mt-1" role="alert">
                {{ session('success') }}
            </div>
            @endif


            <div class="mt-1">
                <table id="resetData" class="table table-bordered table-striped" style="font-size: 11px;">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Produk</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Status</th>
                            <th scope="col">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($produk as $index => $prdk)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{ $prdk->nama_produk }}</td>
                            <td>Rp{{ $prdk->harga }}</td>
                            <td>{{ $prdk->kategori->nama_kategori }}</td>
                            <td>{{ $prdk->status->nama_status }}</td>
                            <td>
                                <a href="{{url('edit/produk/'.$prdk->id_produk)}}"><button class="btn btn-sm btn-outline-warning">Edit</button></a>
                                <button type="button" onclick="hapusProduk('{{ $prdk->id_produk }}')" class="btn btn-sm btn-outline-danger">Hapus</button>

                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @else
    <section>
        <div class="container">
            <div class="position-absolute top-50 start-50 translate-middle">
                <form class="d-flex" method="post" action="{{url('ambil')}}">
                    @csrf
                    <button class="btn btn-outline-success" type="submit">Ambil Data</button>
                </form>
            </div>
        </div>
    </section>
    @endif
</body>
<script>
    setTimeout(function() {
        document.getElementById("success-alert").style.display = "none";
    }, 3000);

    function hapusProduk(id_produk) {
        Swal.fire({
            title: "Konfirmasi Hapus",
            text: "Apakah Anda yakin ingin menghapus produk ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "delete",
                    url: "{{ url('hapus') }}/" + id_produk,
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        if (response.success) {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener(
                                        "mouseenter",
                                        Swal.stopTimer
                                    );
                                    toast.addEventListener(
                                        "mouseleave",
                                        Swal.resumeTimer
                                    );
                                },
                            });
                            Toast.fire({
                                icon: "success",
                                title: response.success,
                            });
                            $("#resetData").load(
                                location.href + " #resetData>*",
                                ""
                            );
                        } else if (response.error) {}
                    },
                });
            }
        });
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

</html>