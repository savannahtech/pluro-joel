import React, { useCallback, useState } from 'react';
import { useDropzone } from 'react-dropzone';
import { CloudArrowUpIcon } from '@heroicons/react/24/outline';
import { cn } from '@/lib/utils';

interface FileUploaderProps {
    onFileSelect: (file: File) => void;
}

export const FileUploader: React.FC<FileUploaderProps> = ({ onFileSelect }) => {
    const [fileName, setFileName] = useState<string | null>(null);

    const onDrop = useCallback((acceptedFiles: File[]) => {
        if (acceptedFiles.length > 0) {
            const file = acceptedFiles[0];
            setFileName(file.name);
            onFileSelect(file);
        }
    }, [onFileSelect]);

    const { getRootProps, getInputProps, isDragActive } = useDropzone({
        onDrop,
        accept: {
            'text/html': ['.html', '.htm'],
        },
        maxFiles: 1,
    });

    return (
        <div className="text-center">
            <div
                {...getRootProps()}
                className={cn(
                    'p-8 border-2 border-dashed rounded-lg cursor-pointer',
                    isDragActive ? 'border-blue-500 bg-blue-50' : 'border-gray-300'
                )}
            >
                <input {...getInputProps()} />
                <CloudArrowUpIcon className="mx-auto h-12 w-12 text-gray-400" />
                <p className="mt-2 text-sm text-gray-600">
                    {isDragActive
                        ? 'Drop the HTML file here'
                        : 'Drag and drop an HTML file here, or click to select'}
                </p>
                {/* Display the name of the uploaded file */}
                {fileName && (
                    <p className="mt-4 text-sm text-gray-700">
                        <strong>Uploaded File:</strong> {fileName}
                    </p>
                )}
            </div>
        </div>
    );
};
