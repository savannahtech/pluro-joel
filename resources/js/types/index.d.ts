export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
};

export interface AccessibilityIssue {
    element: string
    message: string
    type: "warning" | 'error'
    wcagRef: {
        id: string
        level: "A" | "AA" | "AAA"
        url: string
    }
}

export interface AnalysisResult {
    score: number;
    issues: AccessibilityIssue[];
    summary: {
        errors: number;
        totalIssues: number;
        warnings: number;
    };
}
