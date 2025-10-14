import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import PrimaryButton from '@/Components/PrimaryButton';

export default function Show({ company }) {
    return (
        <AuthenticatedLayout
            header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Company Profile</h2>}
        >
            <Head title="Company Profile" />

            <div className="py-12">
                <div className="mx-auto max-w-3xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <h3 className="text-2xl font-semibold">{company.name}</h3>
                            <p className="mt-2 text-sm text-gray-600">{company.industry}</p>

                            <div className="mt-4 text-gray-700">
                                {company.description}
                            </div>

                            <div className="mt-6 grid grid-cols-2 gap-4 text-sm text-gray-700">
                                <div>
                                    <div className="font-medium">Location</div>
                                    <div>{company.location || '—'}</div>
                                </div>
                                <div>
                                    <div className="font-medium">Size</div>
                                    <div>{company.size || '—'}</div>
                                </div>
                            </div>

                            <div className="mt-6 flex items-center gap-3">
                                <Link href={route('company.create')}>
                                    <PrimaryButton>Edit</PrimaryButton>
                                </Link>
                                <Link href={route('dashboard')}>
                                    <PrimaryButton>Back</PrimaryButton>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
