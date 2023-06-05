<x-app-layout>
    @push('css')
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    @endpush

    <x-content-header title="Users"></x-content-header>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <x-status-alert></x-status-alert>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Restaurants</h3>

                            <div class="card-tools">
                                <a href="{{ route('restaurants.create') }}" class="btn btn-link" title="Add Restaurant">
                                    <i class="fas fa-plus"></i>
                                    Add Restaurant
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="restaurants-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Description</th>
                                        <th>Address</th>
                                        <th>Active</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($restaurants as $restaurant)
                                        <tr>
                                            <td>
                                                {{ $restaurant->name }}
                                            </td>
                                            <td>
                                                {{ $restaurant->phone }}
                                            </td>
                                            <td>
                                                {{ $restaurant->email }}
                                            </td>
                                            <td>
                                                {{ $restaurant->description }}
                                            </td>
                                            <td>
                                                {{ $restaurant->address }}
                                            </td>
                                            <td class="{{ $restaurant->active ? 'text-success' : 'text-danger' }}">
                                                {{ $restaurant->active ? 'Yes' : 'No' }}
                                            </td>
                                            <td>
                                                <form action="{{ route('restaurants.update', $restaurant->id) }}"
                                                    method="post" style="display: inline-block">
                                                    @csrf
                                                    @method('put')

                                                    @if ($restaurant->active)
                                                        <input type="hidden" name="active" value="0">
                                                        <button class="btn btn-danger" title="Deactivate"
                                                            type="submit">
                                                            <i class="fas fa-power-off"></i>
                                                        </button>
                                                    @else
                                                        <input type="hidden" name="active" value="1">
                                                        <button class="btn btn-primary" title="Activate" type="submit">
                                                            <i class="fas fa-power-off"></i>
                                                        </button>
                                                    @endif
                                                </form>


                                                <a class="btn btn-secondary"
                                                    href="{{ route('restaurants.edit', $restaurant->id) }}"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    @push('js')
        <!-- DataTables  & Plugins -->
        <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
        <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

        <script>
            $(function() {
                $("#restaurants-table").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo('#restaurants-table_wrapper .col-md-6:eq(0)');
            });
        </script>
    @endpush
</x-app-layout>
