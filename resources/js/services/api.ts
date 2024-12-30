import { AnalysisResult } from '@/types';
import axios from 'axios';

export const analyzeHtml = async (formData: FormData): Promise<AnalysisResult> => {

    const { data } = await axios.post<AnalysisResult>(`http://localhost:8000/api/v1/analyze`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
    });

    return data;
};