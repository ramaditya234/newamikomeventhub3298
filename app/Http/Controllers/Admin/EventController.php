<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class EventController extends Controller
{
    public function index()
    {
        // Mengambil data event beserta relasi kategorinya
        $events = Event::with('category')->latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        // Membutuhkan data kategori untuk dropdown form
        $categories = Category::all();
        return view('admin.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'required',
            'location' => 'required|string',
            'event_date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        // Logika upload gambar poster event
        if ($request->hasFile('image')) {
            $imageName = time() . '-' . Str::slug($request->title) . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/events'), $imageName);
            $data['image'] = 'uploads/events/' . $imageName;
        }

        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Menampilkan detail event spesifik
        $event = Event::with('category')->findOrFail($id);
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Mengambil data event yang akan diedit beserta pilihan kategorinya
        $event = Event::findOrFail($id);
        $categories = Category::all();
        
        return view('admin.events.edit', compact('event', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'category_id' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'required',
            'location' => 'required|string',
            'event_date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            // Gambar tidak required saat update, karena mungkin admin hanya ubah teks
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' 
        ]);

        $data = $request->except('image'); // Ambil semua data kecuali gambar dulu

        // Logika jika admin mengunggah gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if (File::exists(public_path($event->image))) {
                File::delete(public_path($event->image));
            }

            // Upload gambar baru
            $imageName = time() . '-' . Str::slug($request->title) . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/events'), $imageName);
            $data['image'] = 'uploads/events/' . $imageName;
        }

        $event->update($data);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);

        // Hapus file gambar dari folder public sebelum menghapus data di database
        if (File::exists(public_path($event->image))) {
            File::delete(public_path($event->image));
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus!');
    }
}
