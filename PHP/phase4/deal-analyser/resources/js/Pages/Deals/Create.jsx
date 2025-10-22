import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import InputError from '@/Components/InputError';
import { useState, useEffect } from 'react';

export default function Create() {
    const { data, setData, post, processing, errors, reset } = useForm({
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
            forceFormData: true,
            onSuccess: () => {
                reset();
                setImagePreview(null);
            },
        });
    };

    return (
        <AuthenticatedLayout
            header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Upload Deal Image</h2>}
        >
            <Head title="Upload Deal Image" />

            <div className="py-12">
                <div className="mx-auto max-w-3xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <form onSubmit={submit} className="p-8 space-y-6">
                            <div className="text-sm text-gray-600">
                                Upload a clear image related to the deal. Our AI will extract the key details from the image and populate the deal information for you.
                            </div>

                            <div>
                                <InputLabel htmlFor="image" value="Deal Image" />
                                <input
                                    id="image"
                                    type="file"
                                    className="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                    accept="image/jpeg,image/jpg,image/png,image/gif,.jpeg,.jpg"
                                    onChange={(e) => setData('image', e.target.files[0])}
                                    required
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
                                    {processing ? 'Uploading...' : 'Upload Image'}
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}