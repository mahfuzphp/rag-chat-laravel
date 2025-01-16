from fastapi import FastAPI
from pydantic import BaseModel
from typing import List, Union
from sentence_transformers import SentenceTransformer
import numpy as np

app = FastAPI()
model = SentenceTransformer('sentence-transformers/all-MiniLM-L6-v2')

class EmbeddingRequest(BaseModel):
    input: Union[str, List[str]]
    model: str = 'all-MiniLM-L6-v2'

@app.post("/embeddings")
async def create_embedding(request: EmbeddingRequest):
    inputs = request.input if isinstance(request.input, list) else [request.input]
    embeddings = model.encode(inputs)
    
    return {
        "data": [
            {"embedding": embedding.tolist()} 
            for embedding in embeddings
        ]
    }