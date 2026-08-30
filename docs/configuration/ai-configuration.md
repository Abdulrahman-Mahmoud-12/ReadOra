# AI Configuration

ReadOra selects its AI provider with:

```env
AI_PROVIDER=nvidia
```

Supported planned values:

- `nvidia`
- `openrouter`

Required placeholders:

```env
NVIDIA_NIM_API_KEY=
NVIDIA_NIM_BASE_URL=
NVIDIA_NIM_MODEL=

OPENROUTER_API_KEY=
OPENROUTER_BASE_URL=
OPENROUTER_MODEL=
```

Actual credentials must stay in local environment files or deployment secrets.
