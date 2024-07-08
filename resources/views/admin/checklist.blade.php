<table class="w-full bg-white border-gray-300 rounded-lg shadow-md">
    <thead>
        <tr>
            <th class="py-2 px-4 bg-gray-100 border-b-2 text-gray-700 border-gray-300 text-left">Description</th>
        </tr>
    </thead>
    <tbody>
        @foreach($files as $file)
            <tr>
                <td class="py-1 px-4 border-b border-gray-300 text-left">
                    <span class="text-lg font-semibold"><i class="fa-solid fa-check mr-2"></i>{{ $file->description }}</span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
