import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

// The usePage hook is the standard way to access shared data like the auth user
import { usePage } from '@inertiajs/react';

export default function Dashboard({ auth }) {
    // Destructure companyProfile directly from the user prop for cleaner access
    const { companyProfile } = auth.user;

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <div className="mb-4">You're logged in!</div>

                            {/* This is the conditional logic you asked for. */}
                            {companyProfile ? (
                                // If companyProfile exists, show this button
                                <Link
                                    href={route('company.show', companyProfile)}
                                    className="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                >
                                    View Company Profile
                                </Link>
                            ) : (
                                // Otherwise, show this button
                                <Link
                                    href={route('company.create')}
                                    className="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                >
                                    Create Company Profile
                                </Link>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}