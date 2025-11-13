@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-6">Buat Keluarga Baru</h2>

                    <form action="{{ route('user.family.store') }}" method="POST" x-data="groupForm()">
                        @csrf

                        <div class="space-y-6">
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Jenis Grup</label>
                                <select name="type" id="type" x-model="type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="family">Family</option>
                                    <option value="company">Perusahaan</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Family specific initial member inputs -->
                            <div x-show="type === 'family'" x-cloak class="space-y-4">
                                <h3 class="text-lg font-medium">Informasi Awal Keluarga</h3>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Ayah</label>
                                    <input type="text" name="father_name"
                                        class="mt-1 block w-full rounded-md border-gray-300"
                                        value="{{ old('father_name') }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Ibu</label>
                                    <input type="text" name="mother_name"
                                        class="mt-1 block w-full rounded-md border-gray-300"
                                        value="{{ old('mother_name') }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Anak-anak (opsional)</label>
                                    <template x-for="(child, idx) in children" :key="idx">
                                        <div class="flex items-center space-x-2 mt-2">
                                            <input :name="`children[` + idx + `]`" x-model="children[idx]" type="text"
                                                placeholder="Nama anak" class="block w-full rounded-md border-gray-300">
                                            <button type="button" @click="removeChild(idx)"
                                                class="px-2 py-1 bg-red-500 text-white rounded">-</button>
                                        </div>
                                    </template>
                                    <div class="mt-2">
                                        <button type="button" @click="addChild()"
                                            class="px-3 py-1 bg-indigo-600 text-white rounded">Tambah Anak</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Company specific inputs -->
                            <div x-show="type === 'company'" x-cloak class="space-y-4">
                                <h3 class="text-lg font-medium">Informasi Perusahaan</h3>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Direktur</label>
                                    <input type="text" name="company_director"
                                        class="mt-1 block w-full rounded-md border-gray-300"
                                        value="{{ old('company_director') }}">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Manajer</label>
                                    <template x-for="(manager, idx) in managers" :key="idx">
                                        <div class="flex items-center space-x-2 mt-2">
                                            <input :name="`company_managers[` + idx + `]`" x-model="managers[idx]"
                                                type="text" placeholder="Nama manajer"
                                                class="block w-full rounded-md border-gray-300">
                                            <button type="button" @click="removeManager(idx)"
                                                class="px-2 py-1 bg-red-500 text-white rounded">-</button>
                                        </div>
                                    </template>
                                    <div class="mt-2">
                                        <button type="button" @click="addManager()"
                                            class="px-3 py-1 bg-indigo-600 text-white rounded">Tambah Manajer</button>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Staf</label>
                                    <template x-for="(staff, idx) in staffs" :key="idx">
                                        <div class="flex items-center space-x-2 mt-2">
                                            <input :name="`company_staffs[` + idx + `]`" x-model="staffs[idx]" type="text"
                                                placeholder="Nama staf" class="block w-full rounded-md border-gray-300">
                                            <button type="button" @click="removeStaff(idx)"
                                                class="px-2 py-1 bg-red-500 text-white rounded">-</button>
                                        </div>
                                    </template>
                                    <div class="mt-2">
                                        <button type="button" @click="addStaff()"
                                            class="px-3 py-1 bg-indigo-600 text-white rounded">Tambah Staf</button>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Magang (opsional)</label>
                                    <template x-for="(intern, idx) in interns" :key="idx">
                                        <div class="flex items-center space-x-2 mt-2">
                                            <input :name="`company_interns[` + idx + `]`" x-model="interns[idx]" type="text"
                                                placeholder="Nama magang" class="block w-full rounded-md border-gray-300">
                                            <button type="button" @click="removeIntern(idx)"
                                                class="px-2 py-1 bg-red-500 text-white rounded">-</button>
                                        </div>
                                    </template>
                                    <div class="mt-2">
                                        <button type="button" @click="addIntern()"
                                            class="px-3 py-1 bg-indigo-600 text-white rounded">Tambah Magang</button>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="family_name" class="block text-sm font-medium text-gray-700">Nama
                                    Keluarga</label>
                                <input type="text" name="family_name" id="family_name"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required value="{{ old('family_name') }}">
                                @error('family_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi
                                    Keluarga</label>
                                <textarea name="description" id="description" rows="4"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('user.family.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Batal
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Buat Keluarga
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function groupForm() {
            return {
                type: '{{ old('type', 'family') }}',
                children: [],
                managers: [],
                staffs: [],
                interns: [],
                addChild() {
                    this.children.push('');
                },
                removeChild(i) {
                    this.children.splice(i, 1);
                },
                addManager() {
                    this.managers.push('');
                },
                removeManager(i) {
                    this.managers.splice(i, 1);
                },
                addStaff() {
                    this.staffs.push('');
                },
                removeStaff(i) {
                    this.staffs.splice(i, 1);
                },
                addIntern() {
                    this.interns.push('');
                },
                removeIntern(i) {
                    this.interns.splice(i, 1);
                }
            }
        }
    </script>
@endpush
