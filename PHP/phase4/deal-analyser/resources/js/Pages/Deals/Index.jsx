import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import PrimaryButton from '@/Components/PrimaryButton';

// A simple component to render a status badge
const StatusBadge = ({ status }) => {
    const colorClasses = {
        draft: 'bg-gray-100 text-gray-800',
        review: 'bg-yellow-100 text-yellow-800',
        validated: 'bg-blue-100 text-blue-800',
        accepted: 'bg-green-100 text-green-800',
    }[status];

    return (
        <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${colorClasses}`}>
            {status.charAt(0).toUpperCase() + status.slice(1)}
        </span>
    );
};

export default function Index({ deals }) {
    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">Company Deals</h2>
                    <Link href={route('deals.create')}>
                        <PrimaryButton>Propose New Deal</PrimaryButton>
                    </Link>
                </div>
            }
        >
            <Head title="Company Deals" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            {deals.data.length > 0 ? (
                                <ul className="divide-y divide-gray-200">
                                    {deals.data.map((deal) => (
                                        <li key={deal.id} className="py-4 flex justify-between items-center">
                                            <div>
                                                <Link href={route('deals.show', deal.id)} className="text-lg font-semibold text-indigo-600 hover:text-indigo-800">
                                                    {deal.title}
                                                </Link>
                                                <div className="text-sm text-gray-500 mt-1">
                                                    Value: ${Number(deal.value_estimate || 0).toLocaleString()}
                                                </div>
                                            </div>
                                            <div className="flex items-center space-x-4">
                                                <StatusBadge status={deal.status} />
                                                <Link href={route('deals.show', deal.id)} className="text-sm font-medium text-gray-600 hover:text-gray-900">
                                                    View &rarr;
                                                </Link>
                                            </div>
                                        </li>
                                    ))}
                                </ul>
                            ) : (
                                <p>No deals have been created for this company yet.</p>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}