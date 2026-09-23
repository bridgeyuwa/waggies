FROM postgres:18.6-bookworm AS pgvector-builder

ARG PGVECTOR_VERSION=0.8.1

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        build-essential \
        ca-certificates \
        curl \
        postgresql-server-dev-18 \
    && curl -fsSL "https://github.com/pgvector/pgvector/archive/refs/tags/v${PGVECTOR_VERSION}.tar.gz" \
        -o /tmp/pgvector.tar.gz \
    && mkdir -p /tmp/pgvector \
    && tar -xzf /tmp/pgvector.tar.gz --strip-components=1 -C /tmp/pgvector \
    && make -C /tmp/pgvector \
    && make -C /tmp/pgvector install \
    && rm -rf /tmp/pgvector /tmp/pgvector.tar.gz /var/lib/apt/lists/*

FROM postgres:18.6-bookworm

ARG PGVECTOR_VERSION=0.8.1

LABEL org.opencontainers.image.title="Waggies PostgreSQL" \
      org.opencontainers.image.description="PostgreSQL 18.6 with pinned pgvector support" \
      org.opencontainers.image.version="18.6-pgvector-${PGVECTOR_VERSION}"

COPY --from=pgvector-builder /usr/lib/postgresql/18/lib/vector.so /usr/lib/postgresql/18/lib/vector.so
COPY --from=pgvector-builder /usr/share/postgresql/18/extension/vector* /usr/share/postgresql/18/extension/
