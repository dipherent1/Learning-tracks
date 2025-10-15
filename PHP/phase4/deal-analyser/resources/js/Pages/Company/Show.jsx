import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { BuildingOffice2Icon, BriefcaseIcon, MapPinIcon, UsersIcon, BanknotesIcon, PencilSquareIcon, ArrowLeftIcon } from '@heroicons/react/24/outline';

// A reusable component for displaying individual stats
const StatCard = ({ icon, label, value }) => (
    <div className="flex items-start p-4 bg-gray-50 rounded-lg">
        <div className="flex-shrink-0 mr-4">
            {icon}
        </div>
        <div>
            <div className="text-sm font-medium text-gray-500">{label}</div>
            <div className="text-lg font-semibold text-gray-900">{value || 'Not Provided'}</div>
        </div>
    </div>
);

export default function Show({ company }) {
    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Company Profile
                    </h2>
                    <Link
                        href={route('dashboard')}
                        className="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50"
                    >
                        <ArrowLeftIcon className="w-5 h-5 mr-2 -ml-1" />
                        Back to Dashboard
                    </Link>
                </div>
            }
        >
            <Head title="Company Profile" />

            <div className="py-12">
                <div className="mx-auto max-w-4xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        {/* Header Section */}
                        <div className="p-8 border-b border-gray-200">
                            <div className="flex items-center">
                                <div className="flex-shrink-0 w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <BuildingOffice2Icon className="w-8 h-8 text-indigo-600" />
                                </div>
                                <div className="ml-6">
                                    <h3 className="text-3xl font-bold text-gray-900">{company.name}</h3>
                                    <p className="mt-1 text-lg text-indigo-600 flex items-center">
                                        <BriefcaseIcon className="w-5 h-5 mr-2" />
                                        {company.industry || 'No industry specified'}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* Main Content Section */}
                        <div className="p-8">
                            {/* Description */}
                            <div>
                                <h4 className="text-lg font-semibold text-gray-800">About the Company</h4>
                                <p className="mt-2 text-base text-gray-600 leading-relaxed">
                                    {company.description || 'No description provided.'}
                                </p>
                            </div>

                            {/* Stats Grid */}
                            <div className="mt-8">
                                <h4 className="text-lg font-semibold text-gray-800 mb-4">Company Details</h4>
                                <div className="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                    <StatCard
                                        icon={<MapPinIcon className="w-8 h-8 text-gray-400" />}
                                        label="Location"
                                        value={company.location}
                                    />
                                    <StatCard
                                        icon={<UsersIcon className="w-8 h-8 text-gray-400" />}
                                        label="Company Size"
                                        value={company.size ? `${company.size.toLocaleString()} employees` : null}
                                    />
                                    <StatCard
                                        icon={<BanknotesIcon className="w-8 h-8 text-gray-400" />}
                                        label="Annual Revenue"
                                        value={company.revenue ? `$${Number(company.revenue).toLocaleString()}` : null}
                                    />
                                </div>
                            </div>
                        </div>

                        {/* Footer Actions */}
                        <div className="px-8 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                            <Link
                                href={route('company.edit', company.id)}
                                className="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                <PencilSquareIcon className="w-5 h-5 mr-2 -ml-1" />
                                Edit Profile
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}