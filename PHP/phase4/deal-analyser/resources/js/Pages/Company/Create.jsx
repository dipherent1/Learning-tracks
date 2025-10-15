import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import TextInput from '@/Components/TextInput';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import { useEffect } from 'react';

export default function Create({ errors, company }) {
    const { data, setData, post, put, processing, errors: formErrors, reset } = useForm({
        name: company?.name || '',
        industry: company?.industry || '',
        description: company?.description || '',
        location: company?.location || '',
        size: company?.size || '',
        revenue: company?.revenue || '',
    });

    useEffect(() => {
        return () => {
            reset('password', 'password_confirmation');
        };
    }, []);

    const submit = (e) => {
    e.preventDefault();
    if (company) {
        // The `put` helper will automatically send the form data.
        put(route('company.update', company.id)); // <-- Correct
    } else {
        // The `post` helper will automatically send the form data.
        post(route('company.store')); // <-- Correct
    }
};


    return (
        <AuthenticatedLayout
            header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Company Profile</h2>}
        >
            <Head title="Company Profile" />

            <div className="py-12">
                <div className="mx-auto max-w-3xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <form onSubmit={submit} className="space-y-6">
                                <div>
                                    <InputLabel htmlFor="name">Name</InputLabel>
                                    <TextInput
                                        id="name"
                                        value={data.name}
                                        onChange={(e) => setData('name', e.target.value)}
                                        required
                                    />
                                    {formErrors.name && <p className="mt-2 text-sm text-red-600">{formErrors.name}</p>}
                                </div>

                                <div>
                                    <InputLabel htmlFor="industry">Industry</InputLabel>
                                    <TextInput
                                        id="industry"
                                        value={data.industry}
                                        onChange={(e) => setData('industry', e.target.value)}
                                    />
                                    {formErrors.industry && <p className="mt-2 text-sm text-red-600">{formErrors.industry}</p>}
                                </div>

                                <div>
                                    <InputLabel htmlFor="description">Description</InputLabel>
                                    <textarea
                                        id="description"
                                        value={data.description}
                                        onChange={(e) => setData('description', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    />
                                    {formErrors.description && <p className="mt-2 text-sm text-red-600">{formErrors.description}</p>}
                                </div>

                                <div className="grid grid-cols-2 gap-4">
                                    <div>
                                        <InputLabel htmlFor="location">Location</InputLabel>
                                        <TextInput
                                            id="location"
                                            value={data.location}
                                            onChange={(e) => setData('location', e.target.value)}
                                        />
                                        {formErrors.location && <p className="mt-2 text-sm text-red-600">{formErrors.location}</p>}
                                    </div>

                                    <div>
                                        <InputLabel htmlFor="size">Size</InputLabel>
                                        <TextInput
                                            id="size"
                                            type="number"
                                            value={data.size}
                                            onChange={(e) => setData('size', e.target.value)}
                                        />
                                        {formErrors.size && <p className="mt-2 text-sm text-red-600">{formErrors.size}</p>}
                                    </div>
                                </div>

                                <div>
                                    <InputLabel htmlFor="revenue">Revenue</InputLabel>
                                    <TextInput
                                        id="revenue"
                                        type="number"
                                        step="0.01"
                                        value={data.revenue}
                                        onChange={(e) => setData('revenue', e.target.value)}
                                    />
                                    {formErrors.revenue && <p className="mt-2 text-sm text-red-600">{formErrors.revenue}</p>}
                                </div>

                                <div className="flex items-center justify-end gap-4">
                                    <PrimaryButton disabled={processing}>{company ? 'Update' : 'Create'}</PrimaryButton>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
