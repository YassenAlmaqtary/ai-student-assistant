import pytest
from tasks import process_pdf

def test_process_pdf(monkeypatch):
    def fake_post(url, json, headers):
        assert 'lesson_id' in json
        assert 'text' in json
        return type('resp', (), {'status_code': 200})()
    monkeypatch.setattr('requests.post', fake_post)
    # استخدم ملف PDF تجريبي محلي
    result = process_pdf(1, 'sample.pdf')
    assert result is True
