# Python Workers API

- Celery tasks:
  - process_pdf(lesson_id, s3_url)
  - extract_audio_from_video(s3_path)
  - transcribe_audio_whisper(wav)
  - chunk_and_embed(text, lesson_id)
  - generate_summaries(text_or_chunks, styles)
  - rag_answer(index_ref, query)

- FastAPI endpoint (اختياري):
  - POST /internal/task
