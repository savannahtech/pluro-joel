import { FC } from 'react';
import { ExclamationCircleIcon, ExclamationTriangleIcon } from '@heroicons/react/24/outline';
import { AccessibilityIssue } from '@/types';
import { cn } from '@/lib/utils';

interface IssuesListProps {
    issues: AccessibilityIssue[];
}

export const IssuesList: FC<IssuesListProps> = ({ issues }) => {
    console.log("ISSUES: ", issues)
    return (
        <div className='w-full'>
            <h2 className="text-lg font-semibold mb-4">Accessibility Issues</h2>
            <div className="space-y-4">
                {issues.map((issue, index) => (
                    <div
                        key={index}
                        className={cn("w-full p-4 rounded-lg",
                            issue.type === 'error' ? 'bg-red-50' : '',
                            issue.type === 'warning' ? 'bg-yellow-50' : ''
                        )}
                    >
                        <div className='flex items-start gap-2'>
                            {issue.type === 'error' ? (
                                <span>
                                    <ExclamationCircleIcon className="h-5 w-5 text-black inline-block" />
                                </span>
                            ) : (
                                <span>
                                    <ExclamationTriangleIcon className="h-5 w-5 text-black inline-block" />
                                </span>
                            )}
                            <p className="font-medium">{issue.message}</p>
                        </div>

                        <div className="mt-2 flex flex-col justify-start items-start gap-2">
                            <p className="text-sm text-gray-600">
                                Element: <code className="bg-gray-100 px-1 rounded">{issue.element}</code>
                            </p>
                            {issue.wcagRef?.level && (
                                <a href={issue.wcagRef?.url} className="text-sm text-gray-600">
                                    WCAG level: {issue.wcagRef?.level}
                                </a>
                            )}

                            {issue.wcagRef?.url && (
                                <p className="w-full flex items-start gap-1 text-sm text-gray-600">
                                    <span className='text-nowrap'>Reference URL:</span>
                                    <a href={issue.wcagRef?.url} className="max-w-[150px] underline text-xs whitespace-pre-wrap">
                                        {issue.wcagRef?.url}
                                    </a>
                                </p>
                            )}
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
};