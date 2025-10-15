import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import PrimaryButton from '@/Components/PrimaryButton';
import InputError from '@/Components/InputError';
import { useState, useEffect } from 'react';

export default function Create() {
    const { data, setData, post, processing, errors, reset } = useForm({
        title: '',
        description: '',
        value_estimate: '',
        duration_months: '',
        image: null,
    });

    const [imagePreview, setImagePreview] = useState(null);

    useEffect(() => {
        if (data.image) {
            const previewUrl = URL.createObjectURL(data.image);
            setImagePreview(previewUrl);
            return () => URL.revokeObjectURL(previewUrl);
        }
    }, [data.image]);

    const submit = (e) => {
        e.preventDefault();
        post(route('deals.store'), {
            onSuccess: () => reset(),
        });
    };

    return (
        <AuthenticatedLayout
            header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Propose a New Deal</h2>}
        >
            <Head title="Propose Deal" />

            <div className="py-12">
                <div className="mx-auto max-w-3xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <form onSubmit={submit} className="p-8 space-y-6">
                            {/* Title */}
                            <div>
                                <InputLabel htmlFor="title" value="Deal Title" />
                                <TextInput
                                    id="title"
                                    value={data.title}
                                    onChange={(e) => setData('title', e.target.value)}
                                    className="mt-1 block w-full"
                                    required
                                />
                                <InputError message={errors.title} className="mt-2" />
                            </div>

                            {/* Description */}
                            <div>
                                <InputLabel htmlFor="description" value="Description (Optional)" />
                                <textarea
                                    id="description"
                                    value={data.description}
                                    onChange={(e) => setData('description', e.target.value)}
                                    className="mt-1 block w-full h-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                                <InputError message={errors.description} className="mt-2" />
                            </div>
                            
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {/* Estimated Value */}
                                <div>
                                    <InputLabel htmlFor="value_estimate" value="Estimated Value ($)" />
                                    <TextInput
                                        id="value_estimate"
                                        type="number"
                                        step="1000"
                                        value={data.value_estimate}
                                        onChange={(e) => setData('value_estimate', e.target.value)}
                                        className="mt-1 block w-full"
                                        placeholder="e.g., 50000"
                                    />
                                    <InputError message={errors.value_estimate} className="mt-2" />
                                </div>

                                {/* Duration */}
                                <div>
                                    <InputLabel htmlFor="duration_months" value="Duration (Months)" />
                                    <TextInput
                                        id="duration_months"
                                        type="number"
                                        value={data.duration_months}
                                        onChange={(e) => setData('duration_months', e.target.value)}
                                        className="mt-1 block w-full"
                                        placeholder="e.g., 12"
                                    />
                                    <InputError message={errors.duration_months} className="mt-2" />
                                </div>
                            </div>
                            
                            {/* Image Upload */}
                            <div>
                                <InputLabel htmlFor="image" value="Cover Image (Optional)" />
                                <input
                                    id="image"
                                    type="file"
                                    className="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                    onChange={(e) => setData('image', e.target.files[0])}
                                />
                                <InputError message={errors.image} className="mt-2" />
                            </div>
                            
                            {imagePreview && (
                                <div className="mt-4">
                                    <img src={imagePreview} alt="Preview" className="h-48 w-auto rounded-md object-cover" />
                                </div>
                            )}

                            <div className="flex items-center justify-end">
                                <PrimaryButton disabled={processing}>
                                    {processing ? 'Submitting...' : 'Submit Deal'}
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}