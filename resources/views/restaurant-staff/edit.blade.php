<x-app-layout>
    <x-content-header title="Restaurant Staff"></x-content-header>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <x-status-alert></x-status-alert>
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Restaurant Staff</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('restaurant-staff.update', $restaurantStaff->id) }}" method="post">
                            @csrf
                            @method('put')

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" placeholder="Enter Name" value="{{ old('name', $restaurantStaff->staff->name) }}"
                                        name='name' required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" placeholder="Enter Email" value="{{ old('email', $restaurantStaff->staff->email) }}"
                                        name='email' required readonly>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" placeholder="Enter Password" value="{{ old('password') }}"
                                        name='password'>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">Repeat Password</label>
                                    <input type="password"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        id="password_confirmation" placeholder="Repeat Password"
                                        value="{{ old('password_confirmation') }}" name='password_confirmation'
                                    >
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="restaurant_id">Restaurant</label>
                                    <select class="form-control @error('restaurant_id') is-invalid  @enderror"
                                        name="restaurant_id" id="restaurant_id" required>
                                        @foreach ($restaurants as $restaurant)
                                            <option value="{{ $restaurant->id }}"
                                                {{ old('restaurant_id', $restaurantStaff->restaurant_id) == $restaurant->id ? 'selected' : '' }}>
                                                {{ $restaurant->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('restaurant_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</x-app-layout>
