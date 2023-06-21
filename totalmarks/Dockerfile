# syntax=docker/dockerfile:1
FROM golang:1.17-alpine
RUN mkdir /build
WORKDIR /build
RUN export GO111MODULE=on
COPY ./src /build
RUN go build total_marks.go
EXPOSE 5001
CMD ["./total_marks"]

