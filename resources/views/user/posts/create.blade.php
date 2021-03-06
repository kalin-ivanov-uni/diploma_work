<x-layout>
    <x-setting heading="Publish New Post">

        <form method="POST" action="/user/posts" enctype="multipart/form-data">
            @csrf
            <x-form.input name="title" />
{{--            <x-form.input name="slug" />--}}
{{--            <x-form.input name="image[]" type="file" multiple />--}}
            @if(session()->has('error.files'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    {{ session()->get('error.files') }}
                </div>
            @endif
            <x-form.file_upload/>

            <x-form.textarea name="excerpt" />
            <x-form.textarea name="body" />

            <x-form.field>
                <x-form.label name="category"/>
                <select name="category_id" id="category_id" class="border-none rounded-md">
                    @foreach(\App\Models\Category::all() as $category)
                        <option
                            value="{{$category->id}}"
                            {{old('category_id') == $category->id ? "selected" : ""}}
                        >
                            {{ucwords($category->name)}}
                        </option>
                    @endforeach
                </select>
                <x-form.error name="categry"/>
            </x-form.field>

            <p class="block -mb-4 upercase font-bold text-xs text-gray-700" style="">Pick a location of the signal</p>
            @if(session()->has('error.gmap'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    {{ session()->get('error.gmap') }}
                </div>
            @endif
            {{--  GOOGLE MAPS COORDINATES FIELDS     --}}
            <input id="longitude"
                   type="hidden"
                   name="longitude"
                   placeholder="longitude of map"
                   class="lg:bg-transparent py-2 lg:py-0 pl-4 focus-within:outline-none">
            <input id="latitude"
                   type="hidden"
                   name="latitude"
                   placeholder="latitude of map"
                   class="lg:bg-transparent py-2 lg:py-0 pl-4 focus-within:outline-none">

{{--            <x-googlemap.map/>--}}
            <x-buttons.default-button type="button" id="delete-markers"  class="mt-5 -mb-12 bg-red-500">Delete Markers</x-buttons.default-button>


            <x-buttons.submit-button class="mt-5">Publish</x-buttons.submit-button>
        </form>
    </x-setting>
    </section>
</x-layout>
