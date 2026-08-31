# AI Prompts

The librarian system prompt establishes ReadOra's role, supported assistance,
catalog grounding, concise markdown responses, and recommendation behavior.
Book insights use a separate prompt requiring thesis/synopsis, takeaways, ideal
reader guidance, and discussion questions.

User messages are validated for type and length before being sent. Conversation
history is limited to the last six turns and OpenRouter reasoning metadata is
forwarded without modification for continuation requests.
