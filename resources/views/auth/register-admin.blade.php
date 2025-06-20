<x-guest-layout>
  <div class="w-full max-w-md mx-auto mt-10 bg-white dark:bg-gray-800 p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-center">Admin Registration</h2>
    <form method="POST" action="{{ route('register.admin.secret.post') }}">
      @csrf
      <div class="mb-4">
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
      </div>
      <div class="mb-4">
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
      </div>
      <div class="mb-4">
        <x-input-label for="password" :value="__('Password')" />
        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
      </div>
      <div class="mb-6">
        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
      </div>
      <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Register Admin</button>
    </form>
  </div>
</x-guest-layout>