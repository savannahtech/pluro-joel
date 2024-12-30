'use client';

import { FC, useState } from 'react';
import { FileUploader } from '@/Components/FileUploader';
import { IssuesList } from '@/Components/IssuesList';
import { AccessibilityIssue, AnalysisResult } from '@/types';
import { analyzeHtml } from '@/services/api';
import { ScoreDisplay } from '@/Components/ScoreDisplay';

const UploadPage: FC = () => {
    const [issues, setIssues] = useState<AccessibilityIssue[]>([]);
    const [accessibilityScore, setAccessibilityScore] = useState<number | null>(null);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);
    const [summary, setSummary] = useState<Pick<AnalysisResult, 'summary'> | null>(null);

    console.log("SUMMARY: ", summary)

    const handleFileSelect = async (file: File) => {
        setLoading(true);
        setError(null);

        const formData = new FormData();
        formData.append('file', file);

        try {
            const response = await analyzeHtml(formData)

            const { score, issues, summary } = response;
            setAccessibilityScore(score);
            setIssues(issues);
            setSummary({ summary });
        } catch (err: any) {
            setError(err.response?.data?.message || 'Failed to analyze the file.');
            console.log("ERROR: ", err)
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="w-full max-w-[500px] mx-auto p-8 space-y-6">
            <h1 className="text-2xl font-bold">Upload an HTML File</h1>
            <div>
                <FileUploader onFileSelect={handleFileSelect} />

                {loading && <p className="mt-4 text-blue-600">Analyzing file...</p>}
                {error && <p className="mt-4 text-red-600">{error}</p>}
            </div>

            <div className='w-full space-y-[24px]'>
                {accessibilityScore !== null && summary?.summary !== undefined && !loading && (
                    <ScoreDisplay score={accessibilityScore} summary={summary.summary} />
                )}

                {issues.length > 0 && !loading && (
                    <IssuesList issues={issues} />
                )}
            </div>
        </div>
    );
};

export default UploadPage;
