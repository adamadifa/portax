<form action="{{ route('roles.store') }}" id="formcreateRole" method="POST" class="space-y-4">
    @csrf
    <div class="relative group">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors">
            <i class="ti ti-user text-slate-400 group-focus-within:text-[#003d9e]"></i>
        </div>
        <input type="text" name="name" id="role_name" 
            class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#003d9e] focus:ring-4 focus:ring-[#003d9e]/5 transition-all font-medium text-slate-700"
            placeholder="Masukkan Nama Role (e.g. Admin Keuangan)">
        <label class="absolute left-10 -top-2 px-1 bg-white text-[10px] font-bold text-slate-400 transition-colors group-focus-within:text-[#003d9e]">NAMA ROLE</label>
    </div>

    <div class="pt-2">
        <button class="w-full h-11 flex items-center justify-center gap-2 bg-[#003d9e] hover:bg-[#002d75] text-white font-bold rounded-lg shadow-lg shadow-blue-900/20 transition-all active:scale-95" type="submit">
            <i class="fas fa-save opacity-70"></i>
            <span>Simpan Role Baru</span>
        </button>
    </div>
</form>

{{-- Note: Scripts are usually handled by the layout or the parent view if they are common --}}
<script src="{{ asset('assets/js/pages/roles/create.js') }}"></script>
