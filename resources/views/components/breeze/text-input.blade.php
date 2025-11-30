@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => '  sm:text-sm  border-[#334124] focus:border-[#b5b89b] focus:ring-[#b5b89b] rounded-md shadow-sm']) }}>
