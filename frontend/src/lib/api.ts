export type ApiError = {
  message?: string;
  errors?: Record<string, string[]>;
};

const API_BASE = (import.meta.env.VITE_API_URL as string | undefined)?.replace(/\/$/, "") || "/api";

const buildUrl = (path: string) => {
  if (path.startsWith("http://") || path.startsWith("https://")) {
    return path;
  }
  const normalized = path.startsWith("/") ? path : `/${path}`;
  return `${API_BASE}${normalized}`;
};

async function handleResponse<T>(response: Response): Promise<T> {
  const contentType = response.headers.get("content-type") || "";
  const isJson = contentType.includes("application/json");
  const payload = isJson ? await response.json() : await response.text();

  if (!response.ok) {
    const error: ApiError = typeof payload === "object" ? payload : { message: String(payload) };
    throw error;
  }

  return payload as T;
}

export async function apiGet<T>(path: string): Promise<T> {
  const response = await fetch(buildUrl(path), {
    method: "GET",
    headers: {
      Accept: "application/json",
    },
    credentials: "include",
  });
  return handleResponse<T>(response);
}

export async function apiPost<T>(path: string, body: unknown): Promise<T> {
  const response = await fetch(buildUrl(path), {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    credentials: "include",
    body: JSON.stringify(body),
  });
  return handleResponse<T>(response);
}

export async function apiPut<T>(path: string, body: unknown): Promise<T> {
  const response = await fetch(buildUrl(path), {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    credentials: "include",
    body: JSON.stringify(body),
  });
  return handleResponse<T>(response);
}

export async function apiDelete<T>(path: string): Promise<T> {
  const response = await fetch(buildUrl(path), {
    method: "DELETE",
    headers: {
      Accept: "application/json",
    },
    credentials: "include",
  });
  return handleResponse<T>(response);
}
