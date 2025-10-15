import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { ArrowLeftIcon } from '@heroicons/react/24/outline';

const StatusBadge = ({ status, className = '' }) => {
    const colorClasses = {
        draft: 'bg-gray-100 text-gray-800',
        review: 'bg-yellow-100 text-yellow-800',
        validated: 'bg-blue-100 text-blue-800',
        accepted: 'bg-green-100 text-green-800',
    }[status];

    return (
        <span className={`px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full ${colorClasses} ${className}`}>
            {status.charAt(0).toUpperCase() + status.slice(1)}
        </span>
    );
};

export default function Show({ deal }) {
    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">Deal Details</h2>
                    <Link href={route('deals.index')} className="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900">
                        <ArrowLeftIcon className="w-5 h-5 mr-2" />
                        Back to All Deals
                    </Link>
                </div>
            }
        >
            <Head title={deal.title} />

            <div className="py-12">
                <div className="mx-auto max-w-4xl sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        {deal.image_path && (
                            <img src={`/storage/${deal.image_path}`} alt={deal.title} className="w-full h-64 object-cover" />
                        )}

                        <div className="p-8 border-b border-gray-200">
                            <StatusBadge status={deal.status} />
                            <h3 className="mt-2 text-3xl font-bold text-gray-900">{deal.title}</h3>
                            <p className="mt-1 text-md text-gray-500">
                                Proposed by <span className="font-semibold">{deal.company.name}</span>
                            </p>
                        </div>
                        
                        <div className="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                            {/* Left Column (Description) */}
                            <div className="md:col-span-2">
                                <h4 className="text-lg font-semibold text-gray-800">Description</h4>
                                <p className="mt-2 text-base text-gray-600 leading-relaxed whitespace-pre-wrap">
                                    {deal.description || 'No description provided.'}
                                </p>
                            </div>

                            {/* Right Column (Key Details) */}
                            <div className="space-y-6">
                                <div>
                                    <h4 className="text-lg font-semibold text-gray-800">Estimated Value</h4>
                                    <p className="text-2xl font-bold text-indigo-600">
                                        ${Number(deal.value_estimate || 0).toLocaleString()}
                                    </p>
                                </div>
                                <div>
                                    <h4 className="text-lg font-semibold text-gray-800">Duration</h4>
                                    <p className="text-xl font-semibold text-gray-700">
                                        {deal.duration_months ? `${deal.duration_months} months` : 'Not specified'}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* You can add sections for Parties and Risks here later */}
                        <div className="px-8 pb-8">
                            <h4 className="text-lg font-semibold text-gray-800">Associated Parties</h4>
                            {deal.parties.length > 0 ? (
                                <p className="mt-2 text-gray-600">Map over deal.parties here.</p>
                            ) : (
                                <p className="mt-2 text-sm text-gray-500">No parties have been added to this deal yet.</p>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}